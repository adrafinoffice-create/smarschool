<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuruRequest;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        return view('pages.panel.admin.guru.index', [
            'title' => 'Manajemen Guru',
            'pageKey' => 'guru',
            'gurus' => Guru::with('user')->orderBy('nama')->get(),
        ]);
    }

    public function create()
    {
        return view('pages.panel.admin.guru.create', [
            'title' => 'Tambah Guru',
            'pageKey' => 'guru',
        ]);
    }

    public function store(StoreGuruRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'guru',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('pages.panel.admin.guru.edit', [
            'title' => 'Edit Guru',
            'pageKey' => 'guru',
            'guru' => $guru->load('user'),
        ]);
    }

    public function update(StoreGuruRequest $request, Guru $guru)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $guru) {
            $userPayload = [
                'name' => $data['nama'],
                'email' => $data['email'],
                'role' => 'guru',
            ];

            if (! empty($data['password'])) {
                $userPayload['password'] = Hash::make($data['password']);
            }

            $guru->user?->update($userPayload);

            $guru->update([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'alamat' => $data['alamat'],
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            $user = $guru->user;
            $guru->delete();
            $user?->delete();
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
