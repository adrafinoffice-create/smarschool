<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\SesiAbsensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('pages.panel.admin.dashboard', [
            'title' => 'Dashboard Admin',
            'pageKey' => 'dashboard',
            'tahunAjaranAktif' => TahunAjaran::current(),
            'stats' => [
                'guru' => Guru::count(),
                'kelas' => Kelas::count(),
                'siswa' => Siswa::count(),
                'mapel' => MataPelajaran::count(),
                'jadwal' => JadwalPelajaran::count(),
                'sesi_hari_ini' => SesiAbsensi::whereDate('tanggal', now()->toDateString())->count(),
            ],
            'jadwalHariIni' => JadwalPelajaran::with(['pengampu.guru', 'pengampu.mataPelajaran', 'pengampu.kelas'])
                ->where('hari', now()->locale('id')->dayName)
                ->orderBy('jam_mulai')
                ->get(),
        ]);
    }
}
