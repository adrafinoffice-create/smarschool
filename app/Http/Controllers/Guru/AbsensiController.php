<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Pengaturan;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class AbsensiController extends Controller
{
    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);
        $title = 'Absensi Kelas'.$kelas->nama_kelas;

        return view('pages.guru.absensi', compact('title', 'kelas'));
    }
    // Ambil Siswa
    private function getSiswaByNis($nis)
{
    return Siswa::where('nis', $nis)->firstOrFail();
}
// Cek Absensi Hari Ini
private function getAbsensiHariIni($siswaId)
{
    return Absensi::where('siswa_id', $siswaId)
        ->whereDate('tanggal', Carbon::today())
        ->first();
}
//  buat absensi masuk
private function createAbsensi($siswa)
{
    return Absensi::create([
        'kelas_id' => $siswa->kelas_id,
        'siswa_id' => $siswa->id,
        'tanggal' => Carbon::today(),
        'jam_masuk' => Carbon::now()->format('H:i:s'),
        'status' => 'Hadir',
    ]);
}
//  kirim wa
private function kirimWA($message, $phone)
{
    $this->sendWhatsapp($message, $phone);
}
public function storeQr(Request $request)
{
    $siswa = $this->getSiswaByNis($request->siswa_id);
    $pengaturan = Pengaturan::first();
    $now = Carbon::now();

    $absensi = $this->getAbsensiHariIni($siswa->id);

    // ABSEN PULANG
    if ($absensi && $now->format('H:i:s') > $pengaturan->jam_pulang) {
        $absensi->update([
            'jam_pulang' => $now->format('H:i:s')
        ]);

        $this->kirimWA(
            "Siswa $siswa->nama sudah absen pulang jam " . $now->format('H:i:s'),
            $siswa->no_hp
        );

        return response()->json([
            'status' => 'success',
            'message' => 'pulang'
        ]);
    }

    // SUDAH ABSEN
    if ($absensi) {
        return response()->json([
            'status' => 'error',
            'message' => 'sudah absen'
        ]);
    }

    //  ABSEN MASUK
    $absensiBaru = $this->createAbsensi($siswa);

    if ($now->format('H:i:s') > $pengaturan->jam_masuk) {

        $absensiBaru->update(['keterangan' => 'Terlambat']);

        $this->kirimWA(
            "Siswa $siswa->nama terlambat jam " . $now->format('H:i:s'),
            $siswa->no_hp
        );

        return response()->json([
            'status' => 'success',
            'message' => 'terlambat'
        ]);
    }

    //  TEPAT WAKTU
    $absensiBaru->update(['keterangan' => 'Tepat Waktu']);

    $this->kirimWA(
        "Siswa $siswa->nama hadir tepat waktu jam " . $now->format('H:i:s'),
        $siswa->no_hp
    );

    return response()->json([
        'status' => 'success',
        'message' => 'tepat waktu'
    ]);
}

public function storeManual(Request $request)
{
    $siswa = Siswa::findOrFail($request->siswa_id);

    $absensi = $this->getAbsensiHariIni($siswa->id);

    if ($absensi) {
        $absensi->update([
            'status' => $request->status,
        ]);
    } else {
        Absensi::create([
            'kelas_id' => $siswa->kelas_id,
            'siswa_id' => $siswa->id,
            'tanggal' => Carbon::today(),
            'jam_masuk' => Carbon::now()->format('H:i:s'),
            'status' => $request->status,
        ]);
    }

    return redirect()->back()->with('success', 'Absensi berhasil');
}





    // public function storeQr(Request $request)
    // {
    //     $siswa = Siswa::where('nis', $request->siswa_id)->first();
    //     $absensi = $siswa->absensi->where('tanggal', now('Y-m-d'))->first();
    //     $pengaturan = Pengaturan::first();

    //     if(now()->format('H:i:s') > $pengaturan->jam_pulang) {
    //         $absensi->update([
    //             'jam_pulang' =>now()->format('H:i:s')
    //         ]);
    //         $message = "Siswa dengan nama $siswa->nama sudah absen pulang pada jam" . now()->format('H:i:s') . "/n/n Terimah kasih";
    //         $this->sendWhatsapp($message, $siswa->no_hp);
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'pulang'
    //         ]);
    //     } else{


    //      $absensiMasuk = Absensi::create([
    //         'kelas_id' => $siswa->kelas_id,
    //         'siswa_id' => $siswa->id,
    //         'tanggal' => now()->format('Y-m-d'),
    //         'jam_masuk' => now()->format('H:i:s'),
    //         'status' => 'Hadir',

    //     ]);

    //     if ($absensi) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'sudah absen',
    //         ]);
    //     }

    //     if (now()->format('H:i:s') > $pengaturan->jam_masuk) {
    //         $keterangan = 'Terlambat';
    //         $absensiMasuk->update([
    //             'keterangan' => $keterangan,
    //         ]);

    //         $message = "siswa dengan nama $siswa->nama sudah terlambat pada jam".now()->format('H:i:s').'/n/n Terimah kasih';
    //         $this->sendWhatsapp($message, $siswa->no_hp);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Terlambat',
    //         ]);

    //     } else {
    //         $keterangan = 'Tepat Waktu';
    //         $absensi->update([
    //             'keterangan' => $keterangan,
    //         ]);
    //         $message = "siswa dengan nama $siswa->nama sudah sudah pada jam".now()->format('H:i:s').'/n/n Terimah kasih';
    //         $this->sendWhatsapp($message, $siswa->no_hp);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'tepat waktu',
    //         ]);
    //     }
    //     }
    // }
    // public function storeManual(Request $request)
    // {

    //     $siswa = Siswa::findOrFail($request->siswa_id);
    //     $kelas = Kelas::findOrFail($siswa->kelas_id);
    //     $absensi = $kelas->absensi->where('siswa_id', $siswa->id)->where('tanggal', now()->format('Y-m-d'))->first();
    //     // $absensi = $siswa->absensi->where('tanggal', now('Y-m-d'))->first();

    //     if ($absensi) {
    //         $absensi->update([
    //             'status' => $request->status,
    //         ]);
    //     } else {

    //         Absensi::create([
    //             'kelas_id' => $siswa->kelas_id,
    //             'siswa_id' => $siswa->id,
    //             'tanggal' => now()->format('Y-m-d'),
    //             'jam_masuk' => now()->format('H:i:s'),
    //             'status' => $request->status,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Absensi berhasil ditambahkan');
    // }

    // public function sendWhatsapp($message, $phone)
    // {

    //     $curl = curl_init();

    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => 'https://api.fonnte.com/send',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'POST',
    //         CURLOPT_POSTFIELDS => [
    //             'target' => $phone,
    //             'message' => $message,
    //             'countryCode' => '62', // optional
    //         ],
    //         CURLOPT_HTTPHEADER => [
    //             'Authorization:'.env('WA_API_TOKEN'), // change TOKEN to your actual token
    //         ],
    //     ]);

    //     $response = curl_exec($curl);
    //     if (curl_errno($curl)) {
    //         $error_msg = curl_error($curl);
    //     }
    //     curl_close($curl);

    //     if (isset($error_msg)) {
    //         echo $error_msg;
    //     }

    // }
}
