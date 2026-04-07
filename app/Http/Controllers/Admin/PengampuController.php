<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengampuRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengampu;
use App\Models\TahunAjaran;

class PengampuController extends Controller
{
    public function index()
    {
        return view('pages.panel.admin.pengampu.index', [
            'title' => 'Penugasan Guru Mapel',
            'pageKey' => 'pengampu',
            'gurus' => Guru::orderBy('nama')->get(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'mataPelajarans' => MataPelajaran::orderBy('nama')->get(),
            'tahunAjaranAktif' => TahunAjaran::current(),
            'pengampus' => Pengampu::with(['guru', 'mataPelajaran', 'kelas', 'tahunAjaran'])
                ->latest('id')
                ->get(),
        ]);
    }

    public function store(StorePengampuRequest $request)
    {
        $tahunAjaran = TahunAjaran::current() ?? TahunAjaran::latest('id')->firstOrFail();
        $data = $request->validated();

        Pengampu::create($data + ['tahun_ajaran_id' => $tahunAjaran->id]);

        return redirect()->route('admin.pengampu.index')->with('success', 'Penugasan guru berhasil ditambahkan.');
    }

    public function edit(Pengampu $pengampu)
    {
        return view('pages.panel.admin.pengampu.edit', [
            'title' => 'Edit Penugasan Guru',
            'pageKey' => 'pengampu',
            'gurus' => Guru::orderBy('nama')->get(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'mataPelajarans' => MataPelajaran::orderBy('nama')->get(),
            'pengampu' => $pengampu,
        ]);
    }

    public function update(StorePengampuRequest $request, Pengampu $pengampu)
    {
        $data = $request->validated();
        $pengampu->update($data);

        return redirect()->route('admin.pengampu.index')->with('success', 'Penugasan guru berhasil diperbarui.');
    }

    public function destroy(Pengampu $pengampu)
    {
        $pengampu->delete();

        return redirect()->route('admin.pengampu.index')->with('success', 'Penugasan guru berhasil dihapus.');
    }
}
