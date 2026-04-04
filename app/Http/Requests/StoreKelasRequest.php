<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'tahun_ajaran_id' => 'required',
        'wali_kelas_id' => 'required',
        'nama_kelas' => 'required',
    ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'tahun_ajaran_id.required' => 'Tahun ajaran harus diisi',
    //         'wali_kelas_id.required' => 'Wali Kelas harus diisi',
    //         'nama_kelas.required' => 'Kelas harus diisi',
    //     ];
    // }
}
