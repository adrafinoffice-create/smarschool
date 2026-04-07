<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJadwalPelajaranRequest;
use App\Models\JadwalPelajaran;
use App\Models\Pengampu;

class JadwalPelajaranController extends Controller
{
    public function index()
    {
        return view('pages.panel.admin.jadwal.index', [
            'title' => 'Jadwal Pelajaran',
            'pageKey' => 'jadwal',
            'pengampus' => Pengampu::with(['guru', 'mataPelajaran', 'kelas', 'tahunAjaran'])
                ->orderByDesc('id')
                ->get(),
            'jadwals' => JadwalPelajaran::with(['pengampu.guru', 'pengampu.mataPelajaran', 'pengampu.kelas'])
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get(),
        ]);
    }

    public function store(StoreJadwalPelajaranRequest $request)
    {
        $data = $request->validated();

        JadwalPelajaran::create($data + ['is_active' => true]);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwalPelajaran)
    {
        return view('pages.panel.admin.jadwal.edit', [
            'title' => 'Edit Jadwal Pelajaran',
            'pageKey' => 'jadwal',
            'pengampus' => Pengampu::with(['guru', 'mataPelajaran', 'kelas', 'tahunAjaran'])
                ->orderByDesc('id')
                ->get(),
            'jadwal' => $jadwalPelajaran,
        ]);
    }

    public function update(StoreJadwalPelajaranRequest $request, JadwalPelajaran $jadwalPelajaran)
    {
        $data = $request->validated();
        $jadwalPelajaran->update($data);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwalPelajaran)
    {
        $jadwalPelajaran->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}
