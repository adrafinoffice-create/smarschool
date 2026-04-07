<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScanQrAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute tidak valid.',
            'max' => ':attribute terlalu panjang.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode' => 'kode QR siswa',
        ];
    }
}
