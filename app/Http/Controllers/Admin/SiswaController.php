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
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = $request->integer('kelas_id');

        $siswa = Siswa::with('kelas')
            ->when($kelasId, fn ($query) => $query->where('kelas_id', $kelasId))
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        return view('pages.panel.admin.siswa.index', [
            'title' => 'Siswa',
            'pageKey' => 'siswa',
            'siswa' => $siswa,
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'selectedKelasId' => $kelasId,
        ]);
    }

    public function create()
    {
        return view('pages.panel.admin.siswa.create', [
            'title' => 'Tambah Siswa',
            'pageKey' => 'siswa',
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
        ]);
    }

    public function store(StoreSiswaRequest $request)
    {
        $data = $request->validated();

        Siswa::create($data);

        return redirect()->route('admin.siswa.index', ['kelas_id' => $data['kelas_id']])
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return view('pages.panel.admin.siswa.edit', [
            'title' => 'Edit Siswa',
            'pageKey' => 'siswa',
            'siswa' => $siswa,
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
        ]);
    }

    public function update(StoreSiswaRequest $request, Siswa $siswa)
    {
        $data = $request->validated();
        $siswa->update($data);

        return redirect()->route('admin.siswa.index', ['kelas_id' => $siswa->kelas_id])
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $kelasId = $siswa->kelas_id;
        $siswa->delete();

        return redirect()->route('admin.siswa.index', ['kelas_id' => $kelasId])
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function show(string $id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);

        return view('pages.panel.admin.siswa.show', [
            'siswa' => $siswa,
            'qrCode' => $this->generateQr($siswa->nis),
            'title' => 'Detail Siswa',
            'pageKey' => 'siswa',
        ]);
    }

    public function qrCodeDownload($id)
    {
        $siswa = Siswa::findOrFail($id);
        $qr = $this->generateQrPng($siswa->nis);

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-' . $siswa->nis . '.png"');
    }

    public function kartuPelajarDownload($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pages.kartu-pelajar', [
            'siswa' => $siswa,
            'qrCode' => 'data:image/png;base64,' . base64_encode($this->generateQrPng($siswa->nis, 150)),
            'pengaturan' => $pengaturan,
            'logo' => $this->getLogoBase64($pengaturan),
        ])->setPaper('A4', 'portrait');

        return $pdf->download('kartu-' . $siswa->nis . '.pdf');
    }

    public function kartuPelajarPreview($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        $pengaturan = Pengaturan::first();

        return view('pages.kartu-pelajar', [
            'siswa' => $siswa,
            'qrCode' => 'data:image/png;base64,' . base64_encode($this->generateQrPng($siswa->nis, 150)),
            'pengaturan' => $pengaturan,
            'logo' => $this->getLogoBase64($pengaturan),
        ]);
    }

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

    private function getLogoBase64($pengaturan)
    {
        $logoPath = $pengaturan && $pengaturan->logo
            ? public_path('storage/' . $pengaturan->logo)
            : public_path('image/logo.png');

        return file_exists($logoPath)
            ? base64_encode(file_get_contents($logoPath))
            : '';
    }
}
