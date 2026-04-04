<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiswaRequest;
use App\Models\Kelas;
use App\Models\Pengaturan;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
// use id;
// use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SiswaController extends Controller
{

 public function index()
    {
        $title = 'Data Siswa';
        $siswa = Siswa::orderBy('nama')->paginate(10);
        $kelas = Kelas::orderBy('nama_kelas')->get();

       return view('pages.admin.siswa.index', compact('siswa','kelas','title'));

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
    return QrCode::size($size)->generate($nis);
}

// private function generateQrPng($nis, $size = 300)
// {
//     return QrCode::format('png')->size($size)->generate($nis);
// }

private function generateQrSvg($nis, $size = 300)
{
    return QrCode::format('svg')
        ->size($size)
        ->generate($nis);
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



    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $title = 'Data Siswa';
    //     $siswa = Siswa::orderBy('nama')->paginate(10);
    //     $kelas = Kelas::orderBy('nama_kelas')->get();

    //    return view('pages.admin.siswa.index', compact('siswa','kelas','title'));

    // }

    public function filterKelas($idKelas)
    {
        $selectKelas = Kelas::findOrFail($idKelas);
        $siswa = Siswa::orderBy('nama')
        ->where('kelas_id', $idKelas)
        ->paginate(10);
        $kelas = Kelas::orderBy('nama_kelas')->get();
        // $title = 'Data Siswa Kelas' . $selectKelas->nama_kelas;

        return view('pages.admin.siswa.index', compact('siswa','kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $title = 'Tambah Data';
        return view ('pages.admin.siswa.create', compact('kelas','title'));
    }

    public function store(StoreSiswaRequest $request)
{
    $data = $request->validated();

    Siswa::create($data);

    return redirect()->route('siswa.filter', $data['kelas_id'])
        ->with('success','Data siswa berhasil ditambahkan');
}

public function update(StoreSiswaRequest $request, $id)
{
    $siswa = $this->getSiswaById($id);

    $siswa->update($request->validated());

    return redirect()->route('siswa.filter', $siswa->kelas_id)
        ->with('success','Data siswa berhasil diubah');
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

// public function qrCodedownload($id)
// {
//     $siswa = $this->getSiswaById($id);

//     $qr = $this->generateQrPng($siswa->nis);

//     return response($qr)
//         ->header('Content-Type', 'image/png')
//         ->header('Content-Disposition', 'attachment; filename="qr-'.$siswa->nis.'.png"');
// }

private function generateQrPng($nis, $size = 300)
{
    return QrCode::format('png')
        ->size($size)
        ->margin(1)
        ->generate($nis);
}

public function qrCodedownload($id)
{
    $siswa = $this->getSiswaById($id);

    $qr = $this->generateQrPng($siswa->nis);

    return response($qr)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="qr-'.$siswa->nis.'.png"');
}



// public function qrCodedownload($id)
// {
//     $siswa = $this->getSiswaById($id);

//     $qr = $this->generateQrSvg($siswa->nis);

//     return response($qr)
//         ->header('Content-Type', 'image/svg+xml')
//         ->header('Content-Disposition', 'attachment; filename="qr-'.$siswa->nis.'.svg"');
// }


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

    $pdf = Pdf::loadView('kartu-pelajar', $data)
        ->setPaper('A4', 'portrait');

    return $pdf->download('kartu-'.$siswa->nis.'.pdf');
}

public function edit(string $id)
    {
        $title = 'Edit Data Siswa';
         $kelas = Kelas::orderBy('nama_kelas')->get();
         $siswa = Siswa::findOrFail($id);
        return view ('pages.admin.siswa.edit', compact('kelas','siswa','title'));
    }

public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasId = $siswa->kelas_id;
        $siswa->delete();

        return redirect()->route('siswa.filter',$kelasId)->with('success','Data Berhasil dihapus');
    }

}



    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {

    // $validated = $request->validate([
    //      'nis' => 'required|unique:siswa,nis',
    //         'nama' => 'required',
    //         'kelas_id' => 'required',
    //         'tempat_lahir' => 'required',
    //         'tanggal_lahir' => 'required',
    //         'jenis_kelamin' =>'required',
    //         'alamat' => 'required',
    //         'nama_orang_tua' => 'required',
    //         'no_hp' => 'required',
    // ],[
    //      'nis.required' => 'NIS harus diisi',
    //         'nis.unique' => 'NIS sudah terdaftar',
    //         'nama.required' => 'Nama harus diisi',
    //         'kelas_id.required' => 'Kelas harus diisi',
    //         'tempat_lahir.required' => 'Tempat lahir harus diisi',
    //         'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
    //         'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
    //         'alamat.required' => 'Alamat lahir harus diisi',
    //         'nama_orang_tua.required' => 'Nama Orangtua harus diisi',
    //         'no_hp.required' => 'No HP harus diisi',
    // ]);

    // Siswa::create($validated);

    //     return redirect()->route('siswa.filter', $request->kelas_id)
    //     ->with('success','Data siswa berhasil ditambahkan');


    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     $title = 'Detail Siswa';
    //    $siswa = Siswa::findOrFail($id);
    //    $qrCode = QrCode::size(200)->generate($siswa->nis);
    //    return view ('pages.admin.siswa.show', compact('siswa','qrCode','title'));
    // }

    // public function qrCodedownload($id)
    // {
    //     $siswa = Siswa::findOrFail($id);
    //     $qrCodeContent = QrCode::format('png')->size(300)->generate($siswa->nis);
    //     $fileName = 'qr-code-' . $siswa->nama . '-' . $siswa->nis . '.png';

    //     return response($qrCodeContent)
    //     ->header('content-Type', 'image/png')
    //     ->header('content-Disposition', 'attachment; filename="' . $fileName . '"');
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $title = 'Edit Data Siswa';
    //      $kelas = Kelas::orderBy('nama_kelas')->get();
    //      $siswa = Siswa::findOrFail($id);
    //     return view ('pages.admin.siswa.edit', compact('kelas','siswa','title'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {

    // $validated = $request->validate();

    //     Siswa::findOrFail($id)->update($validated);

    //     return redirect()->route('siswa.filter', $request->kelas_id)->with('success','Data siswa berhasil diubah');
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $siswa = Siswa::findOrFail($id);
    //     $kelasId = $siswa->kelas_id;
    //     $siswa->delete();

    //     return redirect()->route('siswa.filter',$kelasId)->with('success','Data Berhasil dihapus');
    // }



