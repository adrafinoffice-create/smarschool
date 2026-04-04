<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
   public function index()
{
    return view('pages.guru.rekap.index', [
        'title' => 'Rekap Absensi'
    ]);
}
public function show($id, Request $request)
{
    $kelas = $this->getKelasById($id);
    $filter = $this->getFilter($request);

    return view('pages.guru.rekap.show', [
        'title' => $this->generateTitle($kelas, $filter['bulan'], $filter['tahun']),
        'kelas' => $kelas,
        'bulan' => $filter['bulan'],
        'tahun' => $filter['tahun'],
    ]);
}
public function exportPDF($id, Request $request)
{
    $kelas = $this->getKelasById($id);
    $filter = $this->getFilter($request);

    $rekap = $this->getRekap($id, $filter['bulan'], $filter['tahun']);

    $data = [
        'title' => $this->generateTitle($kelas, $filter['bulan'], $filter['tahun']),
        'kelas' => $kelas,
        'bulan' => $filter['bulan'],
        'tahun' => $filter['tahun'],
        'totalHadir' => $rekap['Hadir'] ?? 0,
        'totalSakit' => $rekap['Sakit'] ?? 0,
        'totalIzin' => $rekap['Izin'] ?? 0,
        'totalAlpa' => $rekap['Alpa'] ?? 0,
    ];

    $pdf = Pdf::loadView('export.rekap', $data)
        ->setPaper('A4', 'landscape');

    return $pdf->download(
        'Rekap_' . $kelas->nama_kelas . '_' . $filter['bulan'] . '_' . $filter['tahun'] . '.pdf'
    );
}


// ambil kelas
private function getKelasById($id)
{
    return Kelas::findOrFail($id);
}

// ambil filter
private function getFilter(Request $request)
{
    return [
        'bulan' => $request->input('bulan'),
        'tahun' => $request->input('tahun'),
    ];
}
// Generate Title
private function generateTitle($kelas, $bulan, $tahun)
{
    return 'Rekap Absensi ' . $kelas->nama_kelas . ' Bulan ' . $bulan . ' Tahun ' . $tahun;
}
// QUERY REKAP
private function getRekap($kelasId, $bulan, $tahun)
{
    return \App\Models\Absensi::selectRaw("
        status,
        COUNT(*) as total
    ")
    ->where('kelas_id', $kelasId)
    ->whereMonth('tanggal', $bulan)
    ->whereYear('tanggal', $tahun)
    ->groupBy('status')
    ->pluck('total', 'status');
}




    // public function show ($id, Request $request)
    // {
    //     $bulan = $request->input('bulan');
    //     $tahun = $request->input('tahun');
    //     $kelas = Kelas::findOrFail($id);

    //     $title = 'Rekap Absensi' . $kelas->nama_kelas . ' Bulan ' . $bulan . ' Tahun ' . $tahun;
    //     return view('pages.guru.rekap.show', compact('title', 'kelas', 'bulan', 'tahun'));
    // }

    //  public function exportPDF($id, Request $request)
    // {
    //     $bulan = $request->input('bulan');
    //     $tahun = $request->input('tahun');
    //     $kelas = Kelas::findOrFail($id);

    //     $title = 'Rekap Absensi Kelas' . $kelas->nama_kelas . ' Bulan ' . $bulan . ' Tahun ' . $tahun;

    //     $totalHadir = 0;
    //     $totalSakit = 0;
    //     $totalIzin = 0;
    //     $totalAlpa = 0;

    //     foreach($kelas->siswa as $siswa) {
    //         $totalHadir += $siswa->absensi()
    //         ->whereMonth('tanggal', $bulan)
    //         ->whereYear('tanggal', $tahun)
    //         ->where('status', 'Hadir')
    //         ->count();

    //          $totalSakit += $siswa->absensi()
    //         ->whereMonth('tanggal', $bulan)
    //         ->whereYear('tanggal', $tahun)
    //         ->where('status', 'Sakit')
    //         ->count();


    //          $totalIzin += $siswa->absensi()
    //         ->whereMonth('tanggal', $bulan)
    //         ->whereYear('tanggal', $tahun)
    //         ->where('status', 'Izin')
    //         ->count();

    //          $totalAlpa += $siswa->absensi()
    //         ->whereMonth('tanggal', $bulan)
    //         ->whereYear('tanggal', $tahun)
    //         ->where('status', 'Alpa')
    //         ->count();
    //     }

    //     $data = [
    //         'title' => $title,
    //         'kelas' => $kelas,
    //         'bulan' => $bulan,
    //         'tahun' => $tahun,
    //         'totalHadir' => $totalHadir,
    //         'totalSakit' => $totalSakit,
    //         'totalIzin' => $totalIzin,
    //         'totalAlpa' => $totalAlpa,
    //     ];

    //     $pdf = Pdf::loadview('export.rekap', $data);
    //     $pdf->setPaper('A4', 'landscape');

    //     $filename = 'Rekap_Absensi_' . $kelas->nama_kelas . '-' . $bulan . '-' . $tahun . '.pdf';
    //     return $pdf->download($filename);
    // }
}
