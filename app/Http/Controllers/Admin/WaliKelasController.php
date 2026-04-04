<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWaliKelasRequest;
use App\Models\User;
use App\Models\WaliKelas;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WaliKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $wali_kelas = WaliKelas::OrderBy('nama','ASC')->get();
          $title = 'Data Wali Kelas';
    return view ('pages.admin.wali-kelas.index', compact('wali_kelas','title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Wali Kelas';

        return view('pages.admin.wali-kelas.create', compact('title'));
    }


    public function store(StoreWaliKelasRequest $request)
{
    $data = $request->validated();

    $user = $this->createUser($data);

    $data['user_id'] = $user->id;

    WaliKelas::create($data);

    return redirect()->route('wali-kelas.index')
        ->with('success', 'Data berhasil ditambahkan');
}

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //       $request->validate([
    //          'nip' => 'required|unique:wali_kelas,nip',
    //         'nama' => 'required',
    //         'tempat_lahir' => 'required',
    //         'tanggal_lahir' => 'required',
    //         'alamat' =>'required',
    //         'email' => 'required|unique:users,email',
    //         'password' => 'required|min:8'
    //      ],[
    //         'nip.required' => 'NIP harus diisi',
    //         'nip.unique' => 'NIP sudah terdaftar',
    //         'nama.required' => 'Nama Lengkap harus diisi',
    //         'tempat_lahir.required' => 'Tempat lahir harus diisi',
    //         'alamat.required' => 'Alamat harus diisi',
    //         'email.required' => 'Email harus diisi',
    //         'email.unique' => 'Email sudah terdaftar',
    //         'password.required' => 'Password harus diisi',
    //         'password.required' => 'Password minimal 8 karakter'
    //      ]);

    //     $data = $request->all();
    //     $user = User::create([
    //         'name' => $data['nama'],
    //         'email' => $data['email'],
    //         'password' =>Hash::make($data['password']),
    //     ]);

    //     $data['user_id'] = $user->id;
    //     $wali_kelas = WaliKelas::create($data);
    //     return redirect()->route('wali-kelas.index')->with('succes','Data berhasil ditambahkan');
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Data Wali Kelas';
        $wali_kelas = WaliKelas::findOrFail($id);
        return view ('pages.admin.wali-kelas.edit', compact('wali_kelas'));
    }


    public function update(StoreWaliKelasRequest $request, $id)
{
    $wali_kelas = WaliKelas::findOrFail($id);
    $data = $request->validated();

    $user = User::findOrFail($wali_kelas->user_id);
    $this->updateUser($user, $data);

    $wali_kelas->update($data);

    return redirect()->route('wali-kelas.index')
        ->with('success', 'Data berhasil diperbaharui');
}


    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $wali_kelas = WaliKelas::findOrFail($id);
    //     $request->validate([
    //         'nip' => 'required|unique:wali_kelas,nip,' . $id,
    //         'nama' => 'required',
    //         'tempat_lahir' => 'required',
    //         'tanggal_lahir' => 'required',
    //         'alamat' =>'required',
    //         'email' => 'required|unique:users,email,' . $wali_kelas->user_id,

    //     ],[
    //         'nip.required' => 'NIP harus diisi',
    //         'nip.unique' => 'NIP sudah terdaftar',
    //         'nama.required' => 'Nama Lengkap harus diisi',
    //         'tempat_lahir.required' => 'Tempat lahir harus diisi',
    //         'alamat.required' => 'Alamat harus diisi',
    //         'email.required' => 'Email harus diisi',
    //         'email.unique' => 'Email sudah terdaftar',
    //         'password.required' => 'Password minimal 8 karakter'
    //     ]);

    //     $data = $request->all();
    //     if (!empty($data['password'])) {
    //        $password = $data['password'] = Hash::make($data['password']);
    //     }else{
    //         unset($data['password']);
    //     }
    //     $user = User::findOrFail($wali_kelas->user_id)->update($data);
    //     $wali_kelas -> update($data);
    //     return redirect()->route('wali-kelas.index')->with('success','Data berhasil diperbaharui');
    // }

    /**
     * Remove the specified resource from storage.
     */


    private function createUser($data)
{
    return User::create([
        'name' => $data['nama'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);
}

private function updateUser($user, $data)
{
    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    return $user->update([
        'name' => $data['nama'],
        'email' => $data['email'],
        'password' => $data['password'] ?? $user->password,
    ]);
}

public function destroy(string $id)
    {
        WaliKelas::findOrFail($id)->delete();
        return redirect()->back()->with('success','Data berhasil dihapus');
    }

}
