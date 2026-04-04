<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
   public function index()
   {
    $tahun_ajaran = TahunAjaran::OrderBy('tahun_ajaran','DESC')->get();
    $title = 'Data Tahun Ajaran';
    return view ('pages.admin.tahun-ajaran.index', compact('tahun_ajaran','title'));
   }

   public function store(Request $request)
   {
    $request->validate([
        'tahun_ajaran' =>'required',
         'semester' =>'required',
    ],[
        'tahun_ajaran.required' => 'Tahun ajaran harus diisi',
        'semester.required' => 'Semester harus diisi',
    ]);

    TahunAjaran::create($request->all());
    return redirect()->back()->with('success','Data Berhasil ditambahkan');
   }

   public function update(Request $request, $id)
   {
    $request->validate([
        'tahun_ajaran' =>'required',
         'semester' =>'required',
    ],[
        'tahun_ajaran.required' => 'Tahun ajaran harus diisi',
         'semester.required' => 'Semester harus diisi'
    ]);

    TahunAjaran::findOrFail($id)->update($request->all());
    return redirect()->back()->with('success','Data Berhasil diedit');
   }

   public function destroy($id)
   {
    TahunAjaran::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Data Berhasil dihapus');
   }

}
