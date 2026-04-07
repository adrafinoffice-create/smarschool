<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMataPelajaranRequest;
use App\Models\MataPelajaran;

class MataPelajaranController extends Controller
{
    public function index()
    {
        return view('pages.panel.admin.mata-pelajaran.index', [
            'title' => 'Mata Pelajaran',
            'pageKey' => 'mata-pelajaran',
            'mataPelajarans' => MataPelajaran::orderBy('nama')->get(),
        ]);
    }

    public function create()
    {
        return view('pages.panel.admin.mata-pelajaran.create', [
            'title' => 'Tambah Mata Pelajaran',
            'pageKey' => 'mata-pelajaran',
        ]);
    }

    public function store(StoreMataPelajaranRequest $request)
    {
        $data = $request->validated();

        MataPelajaran::create($data);

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('pages.panel.admin.mata-pelajaran.edit', [
            'title' => 'Edit Mata Pelajaran',
            'pageKey' => 'mata-pelajaran',
            'mataPelajaran' => $mataPelajaran,
        ]);
    }

    public function update(StoreMataPelajaranRequest $request, MataPelajaran $mataPelajaran)
    {
        $data = $request->validated();

        $mataPelajaran->update($data);

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
