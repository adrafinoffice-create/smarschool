<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\DetailAbsensi;
use App\Models\Guru;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $kelasId = $request->integer('kelas_id') ?: null;
        $mataPelajaranId = $request->integer('mata_pelajaran_id') ?: null;
        $tanggalMulai = $request->string('tanggal_mulai')->toString() ?: null;
        $tanggalSelesai = $request->string('tanggal_selesai')->toString() ?: null;

        $pengampus = $guru->pengampus()
            ->with(['kelas', 'mataPelajaran'])
            ->get();

        $kelasOptions = $pengampus->pluck('kelas')->filter()->unique('id')->sortBy('nama_kelas')->values();
        $mataPelajaranOptions = $pengampus->pluck('mataPelajaran')->filter()->unique('id')->sortBy('nama')->values();

        $sesiQuery = SesiAbsensi::with(['jadwalPelajaran.pengampu.kelas', 'jadwalPelajaran.pengampu.mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->whereHas('jadwalPelajaran.pengampu', function ($query) use ($kelasId, $mataPelajaranId) {
                $query
                    ->when($kelasId, fn($inner) => $inner->where('kelas_id', $kelasId))
                    ->when($mataPelajaranId, fn($inner) => $inner->where('mata_pelajaran_id', $mataPelajaranId));
            })
            ->when($tanggalMulai, fn($query) => $query->whereDate('tanggal', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn($query) => $query->whereDate('tanggal', '<=', $tanggalSelesai))
            ->withCount([
                'detailAbsensi as hadir_count' => fn($query) => $query->where('status', 'Hadir'),
                'detailAbsensi as izin_count' => fn($query) => $query->where('status', 'Izin'),
                'detailAbsensi as sakit_count' => fn($query) => $query->where('status', 'Sakit'),
                'detailAbsensi as alpa_count' => fn($query) => $query->where('status', 'Alpa'),
            ])
            ->latest('tanggal')
            ->latest('id');

        $summaryBase = DetailAbsensi::query()
            ->join('sesi_absensis', 'detail_absensis.sesi_absensi_id', '=', 'sesi_absensis.id')
            ->join('jadwal_pelajarans', 'sesi_absensis.jadwal_pelajaran_id', '=', 'jadwal_pelajarans.id')
            ->join('pengampus', 'jadwal_pelajarans.pengampu_id', '=', 'pengampus.id')
            ->join('kelas', 'pengampus.kelas_id', '=', 'kelas.id')
            ->join('mata_pelajarans', 'pengampus.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->where('sesi_absensis.guru_id', $guru->id)
            ->when($kelasId, fn($query) => $query->where('pengampus.kelas_id', $kelasId))
            ->when($mataPelajaranId, fn($query) => $query->where('pengampus.mata_pelajaran_id', $mataPelajaranId))
            ->when($tanggalMulai, fn($query) => $query->whereDate('sesi_absensis.tanggal', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn($query) => $query->whereDate('sesi_absensis.tanggal', '<=', $tanggalSelesai));

        $statusColumns = [
            DB::raw('COUNT(*) as total_absensi'),
            DB::raw("SUM(CASE WHEN detail_absensis.status = 'Hadir' THEN 1 ELSE 0 END) as hadir_count"),
            DB::raw("SUM(CASE WHEN detail_absensis.status = 'Izin' THEN 1 ELSE 0 END) as izin_count"),
            DB::raw("SUM(CASE WHEN detail_absensis.status = 'Sakit' THEN 1 ELSE 0 END) as sakit_count"),
            DB::raw("SUM(CASE WHEN detail_absensis.status = 'Alpa' THEN 1 ELSE 0 END) as alpa_count"),
        ];

        $rekapPerKelas = (clone $summaryBase)
            ->select('kelas.id', 'kelas.nama_kelas', ...$statusColumns)
            ->groupBy('kelas.id', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas')
            ->get();

        $rekapPerMapel = (clone $summaryBase)
            ->select('mata_pelajarans.id', 'mata_pelajarans.nama', ...$statusColumns)
            ->groupBy('mata_pelajarans.id', 'mata_pelajarans.nama')
            ->orderBy('mata_pelajarans.nama')
            ->get();

        return view('pages.panel.guru.rekap.index', [
            'title' => 'Rekap Absensi',
            'pageKey' => 'guru-rekap',
            'guru' => $guru,
            'sessions' => $sesiQuery->paginate(12)->withQueryString(),
            'kelasOptions' => $kelasOptions,
            'mataPelajaranOptions' => $mataPelajaranOptions,
            'selectedKelas' => $kelasId,
            'selectedMataPelajaran' => $mataPelajaranId,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'rekapPerKelas' => $rekapPerKelas,
            'rekapPerMapel' => $rekapPerMapel,
        ]);
    }
}
