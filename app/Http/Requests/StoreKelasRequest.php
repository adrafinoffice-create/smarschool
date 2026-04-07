<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajaran,id'],
            'wali_guru_id' => ['nullable', 'exists:gurus,id'],
            'nama_kelas' => ['required', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'max' => ':attribute maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tahun_ajaran_id' => 'tahun ajaran',
            'wali_guru_id' => 'wali kelas',
            'nama_kelas' => 'nama kelas',
        ];
    }
}
