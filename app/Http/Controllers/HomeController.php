<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class HomeController extends Controller
{

    public function dashboardAdmin()
    {

        $title = 'Dashboard';
        $jumlahSiswa = Siswa::count();
        $jumlahWaliKelas = WaliKelas::count();
        $jumlahKelas = Kelas::count();
        $jumlahAdmin = User::count();

        return view('pages.admin.home', compact(
            'title',
            'jumlahSiswa',
            'jumlahWaliKelas',
            'jumlahKelas',
            'jumlahAdmin'
        ));
    }

    public function dashboardGuru()
    {
        $jumlahSiswa = 0;
        $kelas = Kelas::where('wali_kelas_id', Auth::user()->waliKelas->id)->get();
       foreach ($kelas as $item) {
        $jumlahSiswa += Siswa::where('kelas_id', $item->id)->count();
       }


    $title = ' Selamat Datang' . Auth::user()->name;
    return view('pages.guru.dashboard', compact('title','jumlahSiswa','kelas'));
}
}
