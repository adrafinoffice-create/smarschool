<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengaturanRequest;
use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::first();
        $title = 'Pengaturan';

        return view('pages.admin.pengaturan', [
            'title' => $title,
            'pengaturan' => $pengaturan,
            'pageKey' => 'pengaturan',
        ]);
    }

    public function update(StorePengaturanRequest $request)
    {
        $validated = $request->validated();

        if($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logo','public');
        }

        Pengaturan::query()->updateOrCreate(
            ['id' => Pengaturan::query()->value('id') ?? 1],
            $validated
        );
        
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbaharui');
    }
}
