<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $siswa = $this->route('siswa');
        $siswaId = is_object($siswa) ? $siswa->id : $siswa;

        return [
            'nis' => ['required', 'max:50', Rule::unique('siswa', 'nis')->ignore($siswaId)],
            'nama' => ['required', 'max:255'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'tempat_lahir' => ['required', 'max:255'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['required'],
            'nama_orang_tua' => ['required', 'max:255'],
            'no_hp' => ['required', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'in' => ':attribute yang dipilih tidak valid.',
            'max' => ':attribute maksimal :max karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nis' => 'NIS',
            'nama' => 'nama siswa',
            'kelas_id' => 'kelas',
            'tempat_lahir' => 'tempat lahir',
            'tanggal_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'alamat' => 'alamat',
            'nama_orang_tua' => 'nama orang tua',
            'no_hp' => 'nomor HP',
        ];
    }
}
