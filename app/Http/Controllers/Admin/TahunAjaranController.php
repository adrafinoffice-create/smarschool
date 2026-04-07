<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTahunAjaranRequest;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    public function index()
    {
        return view('pages.panel.admin.tahun-ajaran.index', [
            'title' => 'Tahun Ajaran',
            'pageKey' => 'tahun-ajaran',
            'tahunAjarans' => TahunAjaran::orderByDesc('tahun_ajaran')->get(),
        ]);
    }

    public function create()
    {
        return view('pages.panel.admin.tahun-ajaran.create', [
            'title' => 'Tambah Tahun Ajaran',
            'pageKey' => 'tahun-ajaran',
        ]);
    }

    public function store(StoreTahunAjaranRequest $request)
    {
        $data = $request->validated();

        TahunAjaran::create($data);

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('pages.panel.admin.tahun-ajaran.edit', [
            'title' => 'Edit Tahun Ajaran',
            'pageKey' => 'tahun-ajaran',
            'tahunAjaran' => $tahunAjaran,
        ]);
    }

    public function update(StoreTahunAjaranRequest $request, TahunAjaran $tahunAjaran)
    {
        $data = $request->validated();

        $tahunAjaran->update($data);

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function activate(TahunAjaran $tahunAjaran)
    {
        TahunAjaran::query()->update(['is_active' => false]);
        $tahunAjaran->update(['is_active' => true]);

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran aktif berhasil diubah.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();

        return redirect()->route('admin.tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
