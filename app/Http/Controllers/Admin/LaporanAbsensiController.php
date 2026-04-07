<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\SesiAbsensi;
use App\Models\DetailAbsensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LaporanAbsensiController extends Controller
{
    public function index()
    {
        $title = 'Laporan Absensi (Per Kelas)';
        $kelas = Kelas::withCount('siswa')->orderBy('nama_kelas')->get();
        $pageKey = 'laporan-absensi';

        return view('pages.panel.admin.laporan-absensi.index', compact('title', 'kelas', 'pageKey'));
    }

    public function showClass($id, Request $request)
    {
        $kelas = Kelas::findOrFail($id);
        $title = 'Rekap Absensi - ' . $kelas->nama_kelas;

        $mataPelajaranId = $request->query('mata_pelajaran_id');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        // Extract sesi that belong to this class
        $query = SesiAbsensi::with([
            'jadwalPelajaran.pengampu.mataPelajaran',
            'guru'
        ])->whereHas('jadwalPelajaran.pengampu', function ($q) use ($kelas) {
            $q->where('kelas_id', $kelas->id);
        });

        if ($mataPelajaranId) {
            $query->whereHas('jadwalPelajaran.pengampu', function ($q) use ($mataPelajaranId) {
                $q->where('mata_pelajaran_id', $mataPelajaranId);
            });
        }

        if ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }

        // Add counts per sesi
        $query->withCount([
            'detailAbsensi as hadir_count' => fn ($q) => $q->where('status', 'Hadir'),
            'detailAbsensi as izin_count' => fn ($q) => $q->where('status', 'Izin'),
            'detailAbsensi as sakit_count' => fn ($q) => $q->where('status', 'Sakit'),
            'detailAbsensi as alpa_count' => fn ($q) => $q->where('status', 'Alpa'),
        ]);

        $sesi = $query->orderBy('tanggal', 'desc')->orderBy('started_at', 'desc')->paginate(15)->withQueryString();

        // Get mapel options for filter
        $mapelOptions = DB::table('mata_pelajarans')
            ->join('pengampus', 'mata_pelajarans.id', '=', 'pengampus.mata_pelajaran_id')
            ->where('pengampus.kelas_id', $kelas->id)
            ->select('mata_pelajarans.id', 'mata_pelajarans.nama')
            ->distinct()
            ->orderBy('mata_pelajarans.nama')
            ->get();
            
        $pageKey = 'laporan-absensi';

        return view('pages.panel.admin.laporan-absensi.show-class', compact(
            'title', 'kelas', 'sesi', 'mapelOptions', 'mataPelajaranId', 'tanggalMulai', 'tanggalSelesai', 'pageKey'
        ));
    }

    public function showSession($id)
    {
        $sesi = SesiAbsensi::with([
            'jadwalPelajaran.pengampu.mataPelajaran',
            'jadwalPelajaran.pengampu.kelas',
            'guru'
        ])->findOrFail($id);

        $title = 'Detail Sesi - ' . $sesi->jadwalPelajaran->pengampu->mataPelajaran->nama;

        $details = DetailAbsensi::with('siswa')
            ->where('sesi_absensi_id', $id)
            ->get();

        // Summary
        $summary = [
            'Hadir' => $details->where('status', 'Hadir')->count(),
            'Izin' => $details->where('status', 'Izin')->count(),
            'Sakit' => $details->where('status', 'Sakit')->count(),
            'Alpa' => $details->where('status', 'Alpa')->count(),
        ];
        
        $pageKey = 'laporan-absensi';

        return view('pages.panel.admin.laporan-absensi.show-session', compact('title', 'sesi', 'details', 'summary', 'pageKey'));
    }

    public function exportPdf($id, Request $request)
    {
        $kelas = Kelas::findOrFail($id);
        $sesi = $this->getFilteredSesiQuery($kelas->id, $request)->get();

        $data = [
            'title' => 'Laporan Rekap Absensi',
            'kelas' => $kelas,
            'sesi' => $sesi
        ];

        $pdf = Pdf::loadView('pages.panel.admin.laporan-absensi.export-pdf', $data)
            ->setPaper('A4', 'landscape');

        return $pdf->download('Rekap_Sesi_Absensi_' . Str::slug($kelas->nama_kelas) . '.pdf');
    }

    public function exportExcel($id, Request $request)
    {
        $kelas = Kelas::findOrFail($id);
        $sesiRecords = $this->getFilteredSesiQuery($kelas->id, $request)->get();
        
        // Manual import since composer autoload hung
        require_once base_path('vendor/shuchkin/simplexlsxgen/src/SimpleXLSXGen.php');

        $excelData = [];
        $excelData[] = ['<center><b style="font-size:14px;color:#4F46E5;">Laporan Sesi Absensi Kelas: ' . $kelas->nama_kelas . '</b></center>', null, null, null, null, null, null, null];
        $excelData[] = [];
        $excelData[] = [
            '<center><b>Tanggal</b></center>', 
            '<center><b>Mata Pelajaran</b></center>', 
            '<center><b>Guru Pengajar</b></center>', 
            '<center><b>Topik / Materi</b></center>', 
            '<center><b>Hadir</b></center>', 
            '<center><b>Izin</b></center>', 
            '<center><b>Sakit</b></center>', 
            '<center><b>Alpa</b></center>'
        ];

        foreach ($sesiRecords as $row) {
            $excelData[] = [
                '<center>' . $row->tanggal->format('d/m/Y') . '</center>',
                $row->jadwalPelajaran->pengampu->mataPelajaran->nama,
                $row->guru->nama,
                $row->topik ?? '-',
                '<center>' . $row->hadir_count . '</center>',
                '<center>' . $row->izin_count . '</center>',
                '<center>' . $row->sakit_count . '</center>',
                '<center>' . $row->alpa_count . '</center>'
            ];
        }

        $fileName = 'Rekap_Sesi_Absensi_' . Str::slug($kelas->nama_kelas) . '_' . date('Ymd') . '.xlsx';
        
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($excelData);
        $xlsx->mergeCells('A1:H1');

        return response()->streamDownload(function() use ($xlsx) {
            $xlsx->saveAs('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function getFilteredSesiQuery($kelasId, Request $request)
    {
        $mataPelajaranId = $request->query('mata_pelajaran_id');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $query = SesiAbsensi::with([
            'jadwalPelajaran.pengampu.mataPelajaran',
            'guru'
        ])->whereHas('jadwalPelajaran.pengampu', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        });

        if ($mataPelajaranId) {
            $query->whereHas('jadwalPelajaran.pengampu', function ($q) use ($mataPelajaranId) {
                $q->where('mata_pelajaran_id', $mataPelajaranId);
            });
        }

        if ($tanggalMulai) {
            $query->whereDate('tanggal', '>=', $tanggalMulai);
        }

        if ($tanggalSelesai) {
            $query->whereDate('tanggal', '<=', $tanggalSelesai);
        }

        $query->withCount([
            'detailAbsensi as hadir_count' => fn ($q) => $q->where('status', 'Hadir'),
            'detailAbsensi as izin_count' => fn ($q) => $q->where('status', 'Izin'),
            'detailAbsensi as sakit_count' => fn ($q) => $q->where('status', 'Sakit'),
            'detailAbsensi as alpa_count' => fn ($q) => $q->where('status', 'Alpa'),
        ]);

        return $query->orderBy('tanggal', 'desc')->orderBy('started_at', 'desc');
    }
}
