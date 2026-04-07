<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAbsensiMapelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'array'],
            'status.*' => ['required', Rule::in(['Hadir', 'Izin', 'Sakit', 'Alpa'])],
            'keterangan' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'array' => ':attribute harus berupa daftar.',
            'in' => ':attribute yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'status kehadiran',
            'status.*' => 'status kehadiran siswa',
            'keterangan' => 'keterangan',
        ];
    }
}
