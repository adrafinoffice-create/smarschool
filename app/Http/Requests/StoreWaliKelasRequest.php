<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWaliKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       $id = $this->route('wali_kelas');

    return [
        'nip' => 'required|unique:wali_kelas,nip,' . $id,
        'nama' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required',
        'alamat' => 'required',
        'email' => 'required|unique:users,email,' . $this->user_id,
        'password' => $id ? 'nullable|min:8' : 'required|min:6',
    ];
    }

    // public function messages(): array
    // {
    //     return[
    //         'nip.required' => 'NIP harus diisi',
    //         'nip.unique' => 'NIP sudah terdaftar',
    //         'nama.required' => 'Nama Lengkap harus diisi',
    //         'tempat_lahir.required' => 'Tempat lahir harus diisi',
    //         'alamat.required' => 'Alamat harus diisi',
    //         'email.required' => 'Email harus diisi',
    //         'email.unique' => 'Email sudah terdaftar',
    //         'password.required' => 'Password harus diisi',
    //         'password.required' => 'Password minimal 8 karakter'
    //     ];
    // }
}
