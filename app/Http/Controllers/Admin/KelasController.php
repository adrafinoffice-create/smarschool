<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKelasRequest;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\WaliKelas;


class KelasController extends Controller
{
    public function index()
{
    return view('pages.admin.kelas.index', [
        'kelas' => Kelas::with(['tahunAjaran', 'waliKelas'])->get(),
        'title' => 'Data Kelas'
    ]);
}
public function create()
{
    return view('pages.admin.kelas.create', array_merge(
        $this->getFormData(),
        ['title' => 'Tambah Data Kelas']
    ));
}
public function store(StoreKelasRequest $request)
{
    Kelas::create($request->validated());

    return redirect()->route('kelas.index')
        ->with('success', 'Kelas berhasil ditambahkan');
}
public function edit($id)
{
    return view('pages.admin.kelas.edit', array_merge(
        $this->getFormData(),
        [
            'kelas' => $this->getKelasById($id),
            'title' => 'Edit Data Kelas'
        ]
    ));
}
public function update(StoreKelasRequest $request, $id)
{
    $this->getKelasById($id)->update($request->validated());

    return redirect()->route('kelas.index')
        ->with('success', 'Kelas berhasil diperbarui');
}
public function destroy($id)
{
    $this->getKelasById($id)->delete();

    return redirect()->back()
        ->with('success', 'Data berhasil dihapus');
}

private function getFormData()
{
    return [
        'tahun_ajaran' => TahunAjaran::orderBy('tahun_ajaran')->get(),
        'wali_kelas' => WaliKelas::orderBy('nama')->get(),
    ];
}

private function getKelasById($id)
{
    return Kelas::findOrFail($id);
}



    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     $kelas = Kelas::with(['tahunAjaran', 'waliKelas'])->get();
    //     $title = 'Data Kelas';

    //     return view('pages.admin.kelas.index', compact('kelas'));
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     $title = 'Tambah Data Kelas';
    //     $tahun_ajaran = TahunAjaran::orderBy('tahun_ajaran')->get();
    //     $wali_kelas = WaliKelas::orderBy('nama')->get();

    //     return view('pages.admin.kelas.create', compact('tahun_ajaran', 'wali_kelas', 'title'));
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {

    //     $validated = $request->validate([
    //           'tahun_ajaran_id' => 'required',
    //         'wali_kelas_id' => 'required',
    //         'nama_kelas' => 'required',
    //     ],[
    //         'tahun_ajaran_id.required' => 'Tahun ajaran harus diisi',
    //         'wali_kelas_id.required' => 'Wali Kelas harus diisi',
    //         'nama_kelas.required' => 'Kelas harus diisi',
    //     ]);

    //     Kelas::create($validated);

    //     return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     $title = 'Edit Data Kelas';
    //     $tahun_ajaran = TahunAjaran::orderBy('tahun_ajaran')->get();
    //     $wali_kelas = WaliKelas::orderBy('nama')->get();
    //     $kelas = Kelas::findOrFail($id);

    //     return view('pages.admin.kelas.edit', compact('tahun_ajaran', 'wali_kelas', 'kelas'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     $validated = $request->validate([
    //         'tahun_ajaran_id' => 'required',
    //         'wali_kelas_id' => 'required',

    //         'nama_kelas' => 'required',
    //     ], [
    //         'tahun_ajaran_id.required' => 'Tahun ajaran harus diisi',
    //         'wali_kelas_id.required' => 'Wali Kelas harus diisi',

    //         'nama_kelas.required' => 'Kelas harus diisi',
    //     ]);

    //     Kelas::findOrFail($id)->update($validated);

    //     return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     Kelas::findOrFail($id)->delete();

    //     return redirect()->back()->with('success', 'Data Berhasil dihapus');
    // }
}
