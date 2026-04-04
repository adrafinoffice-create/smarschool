<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::first();
        $title = 'Pengaturan';

        return view ('pages.admin.pengaturan', compact('title','pengaturan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required',
            'alamat' => 'required',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
        ],[
            'nama_sekolah.required' => 'Nama sekolah harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'jam_masuk.required' => 'Jam masuk harus diisi',
            'jam_pulang.required' => 'Jam pulang harus diisi'
        ]);

        $data = $request->all();
        if(!empty($request->logo)) {
            $data['logo'] = $request->logo->store('logo','public');
        }

        Pengaturan::first()
        ->update($validated);
        return redirect()->back()->with('success', 'Pengaturan berhasil di perbaharui');
    }
}
