<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Support\JadwalAbsensiState;
use Illuminate\Http\Request;

class JadwalMengajarController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::where('user_id', auth()->id())->firstOrFail();
        $hari = $request->string('hari')->toString();
        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $jadwals = JadwalPelajaran::with(['pengampu.kelas', 'pengampu.mataPelajaran', 'pengampu.tahunAjaran'])
            ->whereHas('pengampu', fn ($query) => $query->where('guru_id', $guru->id))
            ->when($hari, fn ($query) => $query->where('hari', $hari))
            ->orderByRaw("
                CASE hari
                    WHEN 'Senin' THEN 1
                    WHEN 'Selasa' THEN 2
                    WHEN 'Rabu' THEN 3
                    WHEN 'Kamis' THEN 4
                    WHEN 'Jumat' THEN 5
                    WHEN 'Sabtu' THEN 6
                    ELSE 7
                END
            ")
            ->orderBy('jam_mulai')
            ->get()
            ->map(function (JadwalPelajaran $jadwalPelajaran) {
                $jadwalPelajaran->attendance_state = JadwalAbsensiState::forSchedule($jadwalPelajaran);

                return $jadwalPelajaran;
            });

        return view('pages.panel.guru.jadwal.index', [
            'title' => 'Jadwal Mengajar',
            'pageKey' => 'guru-jadwal',
            'guru' => $guru,
            'hariOptions' => $hariOptions,
            'selectedHari' => $hari,
            'jadwals' => $jadwals,
            'hariIni' => JadwalAbsensiState::todayName(),
        ]);
    }
}
