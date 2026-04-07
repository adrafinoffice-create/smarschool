<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalPelajaranController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LaporanAbsensiController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\PengampuController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Guru\AbsensiMapelController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\JadwalMengajarController;
use App\Http\Controllers\Guru\RekapAbsensiController;
use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isGuru;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'guru.dashboard');
});

Route::middleware(['auth', isAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');

    Route::resource('tahun-ajaran', TahunAjaranController::class)
        ->parameters(['tahun-ajaran' => 'tahunAjaran'])
        ->except(['show', 'destroy']);
    Route::delete('tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');
    Route::patch('tahun-ajaran/{tahunAjaran}/activate', [TahunAjaranController::class, 'activate'])->name('tahun-ajaran.activate');

    Route::resource('guru', GuruController::class)->except(['show']);
    Route::resource('mata-pelajaran', MataPelajaranController::class)
        ->parameters(['mata-pelajaran' => 'mataPelajaran'])
        ->except(['show']);
    Route::resource('kelas', KelasController::class)
        ->parameters(['kelas' => 'kelas'])
        ->except(['show']);
    Route::resource('siswa', SiswaController::class)->except(['show']);
    Route::get('siswa/{id}', [SiswaController::class, 'show'])->name('siswa.show');
    Route::get('siswa/{id}/qr-code/download', [SiswaController::class, 'qrCodeDownload'])->name('siswa.qr-code.download');
    Route::get('siswa/{id}/kartu-pelajar/download', [SiswaController::class, 'kartuPelajarDownload'])->name('siswa.kartu-pelajar.download');
    Route::get('siswa/{id}/kartu-pelajar/preview', [SiswaController::class, 'kartuPelajarPreview'])->name('siswa.kartu-pelajar.preview');

    Route::get('enrollment', [EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('enrollment', [EnrollmentController::class, 'store'])->name('enrollment.store');
    Route::delete('enrollment/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');

    Route::get('pengampu', [PengampuController::class, 'index'])->name('pengampu.index');
    Route::post('pengampu', [PengampuController::class, 'store'])->name('pengampu.store');
    Route::get('pengampu/{pengampu}/edit', [PengampuController::class, 'edit'])->name('pengampu.edit');
    Route::put('pengampu/{pengampu}', [PengampuController::class, 'update'])->name('pengampu.update');
    Route::delete('pengampu/{pengampu}', [PengampuController::class, 'destroy'])->name('pengampu.destroy');

    Route::get('jadwal', [JadwalPelajaranController::class, 'index'])->name('jadwal.index');
    Route::post('jadwal', [JadwalPelajaranController::class, 'store'])->name('jadwal.store');
    Route::get('jadwal/{jadwalPelajaran}/edit', [JadwalPelajaranController::class, 'edit'])->name('jadwal.edit');
    Route::put('jadwal/{jadwalPelajaran}', [JadwalPelajaranController::class, 'update'])->name('jadwal.update');
    Route::delete('jadwal/{jadwalPelajaran}', [JadwalPelajaranController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::prefix('laporan-absensi')->name('laporan-absensi.')->group(function () {
        Route::get('/', [LaporanAbsensiController::class, 'index'])->name('index');
        Route::get('/kelas/{kelas}', [LaporanAbsensiController::class, 'showClass'])->name('showClass');
        Route::get('/sesi/{sesi}', [LaporanAbsensiController::class, 'showSession'])->name('showSession');
        Route::get('/kelas/{kelas}/export-pdf', [LaporanAbsensiController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/kelas/{kelas}/export-excel', [LaporanAbsensiController::class, 'exportExcel'])->name('exportExcel');
    });
});

Route::middleware(['auth', isGuru::class])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/', GuruDashboardController::class)->name('dashboard');
    Route::get('jadwal', [JadwalMengajarController::class, 'index'])->name('jadwal.index');
    Route::get('rekap', [RekapAbsensiController::class, 'index'])->name('rekap.index');
    Route::get('jadwal/{jadwalPelajaran}/absensi', [AbsensiMapelController::class, 'show'])->name('absensi.show');
    Route::post('jadwal/{jadwalPelajaran}/absensi', [AbsensiMapelController::class, 'store'])->name('absensi.store');
    Route::post('jadwal/{jadwalPelajaran}/absensi/scan', [AbsensiMapelController::class, 'scan'])->name('absensi.scan');
    Route::get('sesi/{sesiAbsensi}', [AbsensiMapelController::class, 'detail'])->name('absensi.detail');
});
