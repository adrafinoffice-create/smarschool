<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapKehadiranController extends Controller
{

  public function kehadiran()
    {
        $kelas = Kelas::with(['waliKelas','siswa'])->get();
        $title = 'Semua Kelas';

        return view('pages.admin.kehadiran.index', compact('title','kelas'));
    }

private function getKelasById($id)
{
    return Kelas::findOrFail($id);
}

private function getFilter(Request $request)
{
    return [
        'bulan' => $request->input('bulan'),
        'tahun' => $request->input('tahun'),
    ];
}
private function generateTitle($kelas, $bulan, $tahun)
{
    return 'Rekap Absensi ' . $kelas->nama_kelas . ' Bulan ' . $bulan . ' Tahun ' . $tahun;
}
private function getRekapAbsensi($kelasId, $bulan, $tahun)
{
    return Absensi::selectRaw("
        status,
        COUNT(*) as total
    ")
    ->where('kelas_id', $kelasId)
    ->whereMonth('tanggal', $bulan)
    ->whereYear('tanggal', $tahun)
    ->groupBy('status')
    ->pluck('total', 'status');
}
public function exportPDF($id, Request $request)
{
    $kelas = $this->getKelasById($id);
    $filter = $this->getFilter($request);

    $rekap = $this->getRekapAbsensi($id, $filter['bulan'], $filter['tahun']);

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
public function showKehadiran($id)
{
    return view('pages.admin.kehadiran.show', [
        'title' => 'Catatan Kehadiran',
        'kelas' => $this->getKelasById($id),
        'absensi' => Absensi::where('kelas_id', $id)
            ->select('tanggal')
            ->distinct()
            ->orderByDesc('tanggal')
            ->paginate(10)
    ]);
}
public function detailKehadiran($id, $tanggal)
{
    $kelas = $this->getKelasById($id);

    return view('pages.admin.kehadiran.detail', [
        'title' => 'Detail Kehadiran ' . $kelas->nama_kelas . ' - ' . Carbon::parse($tanggal)->translatedFormat('d F Y'),
        'kelas' => $kelas,
        'absensi' => Absensi::where('kelas_id', $id)
            ->where('tanggal', $tanggal)
            ->with('siswa')
            ->get()
    ]);
}





    // public function kehadiran()
    // {
    //     $kelas = Kelas::with(['waliKelas','siswa'])->get();
    //     $title = 'Semua Kelas';

    //     return view('pages.admin.kehadiran.index', compact('title','kelas'));
    // }


    //  public function showKehadiran($id)
    // {
    //     $title = 'Catatan Kehadiran';
    //     $kelas = Kelas::findOrFail($id);
    //     $absensi = Absensi::where('kelas_id', $id)
    //     ->Select('tanggal')
    //     ->orderBy('tanggal', 'desc')
    //     ->distinct()
    //     ->paginate(10);

    //     return view ('pages.admin.kehadiran.show', compact('title','kelas','absensi'));
    // }

    // public function detailKehadiran($id, $tanggal)
    // {
    //     $kelas = Kelas::findOrFail($id);
    //     $title = 'Detail Kehadiran Kelas' . $kelas->nama_kelas . ' Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
    //     $absensi = Absensi::where('kelas_id', $id)
    //     ->where('tanggal', $tanggal)
    //     ->with(['siswa'])
    //     ->get();

    //     return view('pages.admin.kehadiran.detail', compact('title','kelas', 'absensi'));
    // }

    // public function rekap()
    // {
    //     $title = 'Rekap Absensi';
    //     $kelas = Kelas::get();

    //     return view('pages.admin.rekap.index', compact('title','kelas'));
    // }

    // public function showRekap($id, Request $request)
    // {
    //     $bulan = $request->input('bulan');
    //     $tahun = $request->input('tahun');
    //     $kelas = Kelas::findOrFail($id);

    //     $title = 'Rekap Absensi ' . $kelas->nama_kelas . ' Bulan ' . $bulan . ' Tahun ' . $tahun;
    //     return view('pages.admin.rekap.show', compact('title','kelas','bulan', 'tahun'));
    // }

    // public function exportPDF($id, Request $request)
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
