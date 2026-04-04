<?php

use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\RekapKehadiranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\WaliKelasController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\KehadiranController;
use App\Http\Controllers\Guru\RekapAbsensiController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isGuru;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes(['register' => false]);

Route::get('/', function(){
    return redirect()->route('admin.dashboard');
});


Route::middleware(['auth', isGuru::class])->group(function (){
    Route::get('/dashboard',[HomeController::class, 'dashboardGuru'])->name('guru.dashboard');
    Route::get('/absensi/{kelasId}', [AbsensiController::class, 'show'])->name('absensi.show');

    Route::post('absensi/storeManual', [AbsensiController::class,'storeManual'])->name('absensi.storeManual');
    Route::post('absensi/storeQr', [AbsensiController::class,'storeQr'])->name('absensi.storeQr');

    Route::get('kehadiran/{id}', [KehadiranController::class, 'show'])->name('kehadira.show');
    Route::get('kehadiran/{id}/detail/{tanggal}', [KehadiranController::class, 'detail'])->name('kehadiran.detail');

    Route::get('rekap-absensi', [RekapAbsensiController::class, 'index'])->name('guru.rekap-absensi.index');
    Route::get('rekap-absensi/{id}', [RekapAbsensiController::class, 'show'])->name('guru.rekap-absensi.show');

    Route::get('rekap-absensi/{id}/export', [RekapKehadiranController::class, 'exportPDF'])->name('guru.rekap-absensi.export');
});

Route::middleware(['auth', isAdmin::class])->prefix('admin')->group(function() {
Route::get('/', [HomeController::class, 'dashboardAdmin'])->name('admin.dashboard');
Route::resource('tahun-ajaran', TahunAjaranController::class);
Route::resource('wali-kelas', WaliKelasController::class);
Route::resource('kelas', KelasController::class );
Route::resource('siswa', SiswaController::class);
Route::get('siswa/kelas/{id}', [SiswaController::class, 'filterKelas'])->name('siswa.filter');
Route::get('siswa/download-qr-code/{id}', [SiswaController::class, 'qrCodedownload'])->name('siswa.qr-code.download');
Route::get('siswa/download-kartu-pelajar/{id}', [SiswaController::class, 'kartuPelajarDownload'])->name('siswa.kartu-pelajar.download');

Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');

Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

Route::get('kehadiran', [RekapKehadiranController::class, 'kehadiran'])->name('admin.kehadiran');
Route::get('kehadiran/{id}', [RekapKehadiranController::class, 'showKehadiran'])->name('admin.kehadiran.show');
Route::get('kehadiran/{id}/detail/{tanggal}', [RekapKehadiranController::class, 'detailKehadiran'])->name('admin.kehadiran.detail');

Route::get('rekap-absensi', [RekapAbsensiController::class, 'index'])->name('admin.rekap-absensi.index');
Route::get('rekap-absensi/{id}', [RekapAbsensiController::class, 'show'])->name('admin.rekap-absensi.show');

Route::get('rekap-absensi/{id}/export', [RekapKehadiranController::class, 'exportPDF'])->name('admin.rekap-absensi.export');
});

