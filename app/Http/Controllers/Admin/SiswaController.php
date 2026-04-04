<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiswaRequest;
use App\Models\Kelas;
use App\Models\Pengaturan;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class SiswaController extends Controller
{

    public function index()
    {
        $title = 'Data Siswa';
        $siswa = Siswa::orderBy('nama')->paginate(10);
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('pages.admin.siswa.index', compact('siswa', 'kelas', 'title'));
    }

    // Ambil Kelas
    private function getKelas()
    {
        return Kelas::orderBy('nama_kelas')->get();
    }
    // Ambil Siswa
    private function getSiswaById($id)
    {
        return Siswa::findOrFail($id);
    }
    // Generate qr Code
    private function generateQr($nis, $size = 200)
    {
        $qrCode = EndroidQrCode::create($nis)
            ->setSize($size)
            ->setMargin(5);

        $writer = new SvgWriter();
        return $writer->write($qrCode)->getString();
    }

    private function generateQrPng($nis, $size = 300)
    {
        $qrCode = EndroidQrCode::create($nis)
            ->setSize($size)
            ->setMargin(5);

        $writer = new PngWriter();
        return $writer->write($qrCode)->getString();
    }

    // Convert ke Base64
    private function toBase64($data)
    {
        return base64_encode($data);
    }
    // Ambil logo
    private function getLogoBase64($pengaturan)
    {
        $logoPath = $pengaturan->logo
            ? public_path('storage/' . $pengaturan->logo)
            : public_path('image/logo.png');

        return file_exists($logoPath)
            ? base64_encode(file_get_contents($logoPath))
            : '';
    }


    public function filterKelas($idKelas)
    {
        $selectKelas = Kelas::findOrFail($idKelas);
        $siswa = Siswa::orderBy('nama')
            ->where('kelas_id', $idKelas)
            ->paginate(10);
        $kelas = Kelas::orderBy('nama_kelas')->get();
        // $title = 'Data Siswa Kelas' . $selectKelas->nama_kelas;

        return view('pages.admin.siswa.index', compact('siswa', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $title = 'Tambah Data';
        return view('pages.admin.siswa.create', compact('kelas', 'title'));
    }

    public function store(StoreSiswaRequest $request)
    {
        $data = $request->validated();

        Siswa::create($data);

        return redirect()->route('siswa.filter', $data['kelas_id'])
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function update(StoreSiswaRequest $request, $id)
    {
        $siswa = $this->getSiswaById($id);

        $siswa->update($request->validated());

        return redirect()->route('siswa.filter', $siswa->kelas_id)
            ->with('success', 'Data siswa berhasil diubah');
    }

    public function show($id)
    {
        $siswa = $this->getSiswaById($id);

        return view('pages.admin.siswa.show', [
            'siswa' => $siswa,
            'qrCode' => $this->generateQr($siswa->nis),
            'title' => 'Detail Siswa'
        ]);
    }

    public function qrCodedownload($id)
    {
        $siswa = $this->getSiswaById($id);

        $qr = $this->generateQrPng($siswa->nis);

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $siswa->nis . '.png"');
    }


    public function kartuPelajarDownload($id)
    {
        $siswa = $this->getSiswaById($id);
        $pengaturan = Pengaturan::first();

        $data = [
            'siswa' => $siswa,
            'qrCode' => 'data:image/png;base64,' . base64_encode($this->generateQrPng($siswa->nis, 150)),

            // 'qrCode' => $this->toBase64($this->generateQrPng($siswa->nis, 150)),
            'pengaturan' => $pengaturan,
            'logo' => $this->getLogoBase64($pengaturan)
        ];

        $pdf = Pdf::loadView('pages.kartu-pelajar', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->download('kartu-' . $siswa->nis . '.pdf');
    }

    public function edit(string $id)
    {
        $title = 'Edit Data Siswa';
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $siswa = Siswa::findOrFail($id);
        return view('pages.admin.siswa.edit', compact('kelas', 'siswa', 'title'));
    }

    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasId = $siswa->kelas_id;
        $siswa->delete();

        return redirect()->route('siswa.filter', $kelasId)->with('success', 'Data Berhasil dihapus');
    }
}
