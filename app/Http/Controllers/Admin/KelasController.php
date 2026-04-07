<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKelasRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;

class KelasController extends Controller
{
    public function index()
    {
        return view('pages.panel.admin.kelas.index', [
            'title' => 'Kelas',
            'pageKey' => 'kelas',
            'kelas' => Kelas::with(['tahunAjaran', 'waliGuru', 'enrollments'])->orderBy('nama_kelas')->get(),
        ]);
    }

    public function create()
    {
        return view('pages.panel.admin.kelas.create', [
            'title' => 'Tambah Kelas',
            'pageKey' => 'kelas',
            'tahunAjarans' => TahunAjaran::orderByDesc('tahun_ajaran')->get(),
            'gurus' => Guru::orderBy('nama')->get(),
        ]);
    }

    public function store(StoreKelasRequest $request)
    {
        $data = $request->validated();
        $data['wali_kelas_id'] = $this->resolveLegacyWaliKelasId($data['wali_guru_id'] ?? null);

        Kelas::create($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        return view('pages.panel.admin.kelas.edit', [
            'title' => 'Edit Kelas',
            'pageKey' => 'kelas',
            'kelas' => $kelas,
            'tahunAjarans' => TahunAjaran::orderByDesc('tahun_ajaran')->get(),
            'gurus' => Guru::orderBy('nama')->get(),
        ]);
    }

    public function update(StoreKelasRequest $request, Kelas $kelas)
    {
        $data = $request->validated();
        $data['wali_kelas_id'] = $this->resolveLegacyWaliKelasId($data['wali_guru_id'] ?? null);

        $kelas->update($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    private function resolveLegacyWaliKelasId(?int $guruId): ?int
    {
        if (! $guruId) {
            return null;
        }

        return Guru::query()
            ->whereKey($guruId)
            ->value('legacy_wali_kelas_id');
    }
}
