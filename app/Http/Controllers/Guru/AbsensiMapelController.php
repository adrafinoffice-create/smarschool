<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScanQrAbsensiRequest;
use App\Http\Requests\StoreAbsensiMapelRequest;
use App\Models\DetailAbsensi;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\KelasSiswa;
use App\Models\SesiAbsensi;
use App\Models\Siswa;
use App\Support\JadwalAbsensiState;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiMapelController extends Controller
{
    public function show(JadwalPelajaran $jadwalPelajaran)
    {
        $guru = $this->getGuru();
        $this->ensureOwner($guru->id, $jadwalPelajaran);

        $jadwalPelajaran->load(['pengampu.guru', 'pengampu.mataPelajaran', 'pengampu.kelas', 'pengampu.tahunAjaran']);

        $sesi = SesiAbsensi::with('detailAbsensi')
            ->where('jadwal_pelajaran_id', $jadwalPelajaran->id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        $enrollments = KelasSiswa::with('siswa')
            ->where('kelas_id', $jadwalPelajaran->pengampu->kelas_id)
            ->where('tahun_ajaran_id', $jadwalPelajaran->pengampu->tahun_ajaran_id)
            ->where('status', 'aktif')
            ->orderBy(
                Siswa::select('nama')
                    ->whereColumn('siswa.id', 'kelas_siswa.siswa_id')
            )
            ->get();

        $details = $sesi
            ? $sesi->detailAbsensi->keyBy('siswa_id')
            : collect();

        return view('pages.panel.guru.absensi.show', [
            'title' => 'Absensi Mapel',
            'pageKey' => 'guru-jadwal',
            'jadwal' => $jadwalPelajaran,
            'guru' => $guru,
            'sesi' => $sesi,
            'enrollments' => $enrollments,
            'details' => $details,
            'attendanceState' => JadwalAbsensiState::forSchedule($jadwalPelajaran),
        ]);
    }

    public function store(StoreAbsensiMapelRequest $request, JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $guru = $this->getGuru();
        $this->ensureOwner($guru->id, $jadwalPelajaran);

        if (! $this->canTakeAttendance($jadwalPelajaran)) {
            return redirect()
                ->route('guru.absensi.show', $jadwalPelajaran)
                ->withErrors(['jadwal' => 'Absensi hanya dapat dilakukan saat jadwal pelajaran sedang berlangsung.']);
        }

        $enrolledIds = KelasSiswa::where('kelas_id', $jadwalPelajaran->pengampu->kelas_id)
            ->where('tahun_ajaran_id', $jadwalPelajaran->pengampu->tahun_ajaran_id)
            ->where('status', 'aktif')
            ->pluck('siswa_id')
            ->all();

        DB::transaction(function () use ($request, $jadwalPelajaran, $guru, $enrolledIds) {
            $sesi = SesiAbsensi::firstOrCreate(
                [
                    'jadwal_pelajaran_id' => $jadwalPelajaran->id,
                    'tanggal' => now()->toDateString(),
                ],
                [
                    'guru_id' => $guru->id,
                    'started_at' => now(),
                ]
            );

            foreach ($enrolledIds as $siswaId) {
                DetailAbsensi::updateOrCreate(
                    [
                        'sesi_absensi_id' => $sesi->id,
                        'siswa_id' => $siswaId,
                    ],
                    [
                        'status' => $request->input("status.$siswaId", 'Alpa'),
                        'keterangan' => $request->input("keterangan.$siswaId"),
                        'checked_at' => now(),
                    ]
                );
            }

            $sesi->update([
                'guru_id' => $guru->id,
                'closed_at' => now(),
            ]);
        });

        return redirect()->route('guru.absensi.show', $jadwalPelajaran)->with('success', 'Absensi sesi berhasil disimpan.');
    }

    public function scan(ScanQrAbsensiRequest $request, JadwalPelajaran $jadwalPelajaran): JsonResponse
    {
        $guru = $this->getGuru();
        $this->ensureOwner($guru->id, $jadwalPelajaran);

        if (! $this->canTakeAttendance($jadwalPelajaran)) {
            return response()->json([
                'message' => 'Scan QR hanya dapat dilakukan saat jadwal pelajaran sedang berlangsung.',
            ], 422);
        }

        $siswa = $this->resolveStudentFromCode($request->validated('kode'));

        if (! $siswa) {
            return response()->json([
                'message' => 'Kode QR siswa tidak dikenali.',
            ], 422);
        }

        $enrollment = KelasSiswa::with('siswa')
            ->where('kelas_id', $jadwalPelajaran->pengampu->kelas_id)
            ->where('tahun_ajaran_id', $jadwalPelajaran->pengampu->tahun_ajaran_id)
            ->where('status', 'aktif')
            ->where('siswa_id', $siswa->id)
            ->first();

        if (! $enrollment) {
            return response()->json([
                'message' => 'Siswa ini tidak terdaftar pada kelas dan sesi yang sedang berlangsung.',
            ], 422);
        }

        $detail = DB::transaction(function () use ($guru, $jadwalPelajaran, $siswa) {
            $sesi = SesiAbsensi::firstOrCreate(
                [
                    'jadwal_pelajaran_id' => $jadwalPelajaran->id,
                    'tanggal' => now()->toDateString(),
                ],
                [
                    'guru_id' => $guru->id,
                    'started_at' => now(),
                ]
            );

            $existing = DetailAbsensi::where('sesi_absensi_id', $sesi->id)
                ->where('siswa_id', $siswa->id)
                ->first();

            if ($existing && $existing->status === 'Hadir' && $existing->checked_at) {
                return [$sesi, $existing, true];
            }

            $detail = DetailAbsensi::updateOrCreate(
                [
                    'sesi_absensi_id' => $sesi->id,
                    'siswa_id' => $siswa->id,
                ],
                [
                    'status' => 'Hadir',
                    'keterangan' => null,
                    'checked_at' => now(),
                ]
            );

            $sesi->update([
                'guru_id' => $guru->id,
                'closed_at' => now(),
            ]);

            return [$sesi, $detail->fresh(), false];
        });

        [$sesi, $detail, $alreadyPresent] = $detail;

        return response()->json([
            'message' => $alreadyPresent
                ? "Siswa {$siswa->nama} sudah tercatat hadir pada sesi ini."
                : "Kehadiran {$siswa->nama} berhasil dicatat.",
            'already_present' => $alreadyPresent,
            'sesi_id' => $sesi->id,
            'detail' => [
                'siswa_id' => $siswa->id,
                'nis' => $siswa->nis,
                'nama' => $siswa->nama,
                'status' => $detail->status,
                'checked_at' => optional($detail->checked_at)->format('H:i:s'),
                'keterangan' => $detail->keterangan,
            ],
        ]);
    }

    public function detail(SesiAbsensi $sesiAbsensi)
    {
        $guru = $this->getGuru();

        abort_unless($sesiAbsensi->guru_id === $guru->id, 403);

        return view('pages.panel.guru.absensi.detail', [
            'title' => 'Detail Sesi Absensi',
            'pageKey' => 'guru-rekap',
            'guru' => $guru,
            'sesi' => $sesiAbsensi->load([
                'jadwalPelajaran.pengampu.guru',
                'jadwalPelajaran.pengampu.mataPelajaran',
                'jadwalPelajaran.pengampu.kelas',
                'detailAbsensi.siswa',
            ]),
        ]);
    }

    private function getGuru(): Guru
    {
        return Guru::where('user_id', Auth::id())->firstOrFail();
    }

    private function ensureOwner(int $guruId, JadwalPelajaran $jadwalPelajaran): void
    {
        abort_unless($jadwalPelajaran->pengampu->guru_id === $guruId, 403);
    }

    private function canTakeAttendance(JadwalPelajaran $jadwalPelajaran): bool
    {
        return JadwalAbsensiState::forSchedule($jadwalPelajaran)['can_take_attendance'];
    }

    private function resolveStudentFromCode(string $code): ?Siswa
    {
        $trimmedCode = trim($code);
        $payload = json_decode($trimmedCode, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($payload)) {
            $nis = $payload['nis'] ?? $payload['kode'] ?? null;
            $siswaId = $payload['siswa_id'] ?? $payload['id'] ?? null;

            if ($nis) {
                return Siswa::where('nis', trim((string) $nis))->first();
            }

            if ($siswaId) {
                return Siswa::find($siswaId);
            }
        }

        $normalizedCode = preg_replace('/^(siswa|nis)\s*[:#-]?\s*/i', '', $trimmedCode) ?: $trimmedCode;

        return Siswa::where('nis', $normalizedCode)
            ->orWhere('id', ctype_digit($normalizedCode) ? (int) $normalizedCode : 0)
            ->first();
    }
}
