<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Absensi;
use Carbon\Carbon;

class KehadiranController extends Controller
{

private function getKelasById($id)
{
    return Kelas::findOrFail($id);
}
private function formatTitleDetail($kelas, $tanggal)
{
    return 'Detail Kehadiran ' . $kelas->nama_kelas . ' - ' .
        Carbon::parse($tanggal)->translatedFormat('d F Y');
}
public function show($id)
{
    return view('pages.guru.kehadiran.show', [
        'title' => 'Catatan Kehadiran',
        'kelas' => $this->getKelasById($id),
        'absensi' => Absensi::where('kelas_id', $id)
            ->select('tanggal')
            ->distinct()
            ->orderByDesc('tanggal')
            ->paginate(10)
    ]);
}

public function detail($id, $tanggal)
{
    $kelas = $this->getKelasById($id);

    return view('pages.guru.kehadiran.detail', [
        'title' => $this->formatTitleDetail($kelas, $tanggal),
        'kelas' => $kelas,
        'absensi' => Absensi::where('kelas_id', $id)
            ->whereDate('tanggal', $tanggal)
            ->with('siswa')
            ->get()
    ]);
}





    // public function show($id)
    // {
    //     $title = 'Catatan Kehadiran';
    //     $kelas = Kelas::findOrFail($id);
    //     $absensi = Absensi::where('kelas_id', $id)
    //     ->Select('tanggal')
    //     ->orderBy('tanggal', 'desc')
    //     ->distinct()
    //     ->paginate(10);

    //     return view ('pages.guru.kehadiran.show', compact('title','kelas','absensi'));
    // }

    // public function detail($id, $tanggal)
    // {
    //     $kelas = Kelas::findOrFail($id);
    //     $title = 'Detail Kehadiran Kelas' . $kelas->nama_kelas . ' Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
    //     $absensi = Absensi::where('kelas_id', $id)
    //     ->where('tanggal', $tanggal)
    //     ->with(['siswa'])
    //     ->get();

    //     return view('pages.guru.kehadiran.detail', compact('title','kelas', 'absensi'));
    // }
}
