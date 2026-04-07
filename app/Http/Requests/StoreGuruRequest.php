<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guru = $this->route('guru');
        $guruId = is_object($guru) ? $guru->id : $guru;
        $userId = is_object($guru) ? $guru->user_id : null;

        return [
            'nip' => ['required', 'max:20', Rule::unique('gurus', 'nip')->ignore($guruId)],
            'nama' => ['required', 'max:100'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$guruId ? 'nullable' : 'required', 'min:8'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tempat_lahir' => ['required', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'email' => 'Format email tidak valid.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'in' => ':attribute yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nip' => 'NIP',
            'nama' => 'nama guru',
            'email' => 'email',
            'password' => 'password',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'alamat' => 'alamat',
        ];
    }
}
