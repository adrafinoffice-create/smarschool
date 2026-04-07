<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\SesiAbsensi;
use App\Support\JadwalAbsensiState;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $guru = Guru::where('user_id', auth()->id())->firstOrFail();
        $jadwalHariIni = JadwalPelajaran::with(['pengampu.guru', 'pengampu.mataPelajaran', 'pengampu.kelas'])
            ->whereHas('pengampu', fn ($query) => $query->where('guru_id', $guru->id))
            ->where('hari', JadwalAbsensiState::todayName())
            ->orderBy('jam_mulai')
            ->get()
            ->map(function (JadwalPelajaran $jadwalPelajaran) {
                $jadwalPelajaran->attendance_state = JadwalAbsensiState::forSchedule($jadwalPelajaran);

                return $jadwalPelajaran;
            });

        $riwayatSesi = SesiAbsensi::with(['jadwalPelajaran.pengampu.mataPelajaran', 'jadwalPelajaran.pengampu.kelas'])
            ->where('guru_id', $guru->id)
            ->latest('tanggal')
            ->limit(10)
            ->get();

        return view('pages.panel.guru.dashboard', [
            'title' => 'Dashboard Guru',
            'pageKey' => 'guru-dashboard',
            'guru' => $guru,
            'jadwalHariIni' => $jadwalHariIni,
            'riwayatSesi' => $riwayatSesi,
            'ringkasan' => [
                'jadwal_hari_ini' => $jadwalHariIni->count(),
                'sesi_bulan_ini' => SesiAbsensi::where('guru_id', $guru->id)
                    ->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->count(),
                'kelas_diampu' => $guru->pengampus()->distinct()->count('kelas_id'),
            ],
        ]);
    }
}
