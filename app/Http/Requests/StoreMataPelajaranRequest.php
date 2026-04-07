<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMataPelajaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mataPelajaran = $this->route('mataPelajaran');
        $mataPelajaranId = is_object($mataPelajaran) ? $mataPelajaran->id : $mataPelajaran;

        return [
            'kode' => ['required', 'max:20', Rule::unique('mata_pelajarans', 'kode')->ignore($mataPelajaranId)],
            'nama' => ['required', 'max:255'],
            'deskripsi' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'max' => ':attribute maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kode' => 'kode mata pelajaran',
            'nama' => 'nama mata pelajaran',
            'deskripsi' => 'deskripsi',
        ];
    }
}
