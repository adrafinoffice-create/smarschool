<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Kelas;
use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::current() ?? TahunAjaran::latest('id')->first();
        $kelasId = $request->integer('kelas_id');

        $kelas = Kelas::with(['tahunAjaran', 'waliGuru'])->orderBy('nama_kelas')->get();
        $selectedKelas = $kelasId ? $kelas->firstWhere('id', $kelasId) : $kelas->first();

        $enrollments = collect();
        $availableSiswas = collect();

        if ($tahunAjaran) {
            $activeEnrollments = KelasSiswa::with(['siswa', 'kelas'])
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->where('status', 'aktif')
                ->get();

            $activeSiswaIds = $activeEnrollments->pluck('siswa_id');

            $availableSiswas = Siswa::query()
                ->whereNotIn('id', $activeSiswaIds)
                ->orderBy('nama')
                ->get();
        }

        if ($selectedKelas && $tahunAjaran) {
            $enrollments = KelasSiswa::with('siswa')
                ->where('kelas_id', $selectedKelas->id)
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->where('status', 'aktif')
                ->orderByDesc('id')
                ->get();
        }

        $kelasStats = $kelas->map(function ($itemKelas) use ($tahunAjaran) {
            $totalAktif = 0;

            if ($tahunAjaran) {
                $totalAktif = KelasSiswa::query()
                    ->where('kelas_id', $itemKelas->id)
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('status', 'aktif')
                    ->count();
            }

            return [
                'kelas' => $itemKelas,
                'total_aktif' => $totalAktif,
            ];
        });

        return view('pages.panel.admin.enrollment.index', [
            'title' => 'Enroll Siswa ke Kelas',
            'pageKey' => 'enrollment',
            'kelas' => $kelas,
            'kelasStats' => $kelasStats,
            'selectedKelas' => $selectedKelas,
            'tahunAjaran' => $tahunAjaran,
            'availableSiswas' => $availableSiswas,
            'enrollments' => $enrollments,
        ]);
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $data = $request->validated();

        $tahunAjaran = TahunAjaran::current() ?? TahunAjaran::latest('id')->firstOrFail();

        DB::transaction(function () use ($data, $tahunAjaran) {
            foreach ($data['siswa_id'] as $siswaId) {
                KelasSiswa::updateOrCreate([
                    'siswa_id' => $siswaId,
                    'kelas_id' => $data['kelas_id'],
                    'tahun_ajaran_id' => $tahunAjaran->id,
                ], [
                    'status' => 'aktif',
                    'tanggal_masuk' => now()->toDateString(),
                ]);

                Siswa::where('id', $siswaId)->update(['kelas_id' => $data['kelas_id']]);
            }
        });

        return redirect()->route('admin.enrollment.index', ['kelas_id' => $data['kelas_id']])
            ->with('success', 'Enrollment siswa berhasil ditambahkan.');
    }

    public function destroy(KelasSiswa $enrollment)
    {
        $kelasId = $enrollment->kelas_id;
        $enrollment->update(['status' => 'nonaktif']);

        return redirect()->route('admin.enrollment.index', ['kelas_id' => $kelasId])
            ->with('success', 'Enrollment siswa berhasil dinonaktifkan.');
    }
}
