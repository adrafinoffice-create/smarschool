<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
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

    $id = $this->route('siswa');

    return [
        'nis' => 'required|unique:siswa,nis,' . $id,
        'nama' => 'required',
        'kelas_id' => 'required',
        'tempat_lahir' => 'required',
        'tanggal_lahir' => 'required',
        'jenis_kelamin' => 'required',
        'alamat' => 'required',
        'nama_orang_tua' => 'required',
        'no_hp' => 'required',
    ];
        // return [
        //     'nis' => 'required|unique:siswa,nis',
        //     'nama' => 'required',
        //     'kelas_id' => 'required',
        //     'tempat_lahir' => 'required',
        //     'tanggal_lahir' => 'required',
        //     'jenis_kelamin' =>'required',
        //     'alamat' => 'required',
        //     'nama_orang_tua' => 'required',
        //     'no_hp' => 'required',
        // ];
    }

    //  public function messages(): array
    //  {
    //     return [
    //          'nis.required' => 'NIS harus diisi',
    //         'nis.unique' => 'NIS sudah terdaftar',
    //         'nama.required' => 'Nama harus diisi',
    //         'kelas_id.required' => 'Kelas harus diisi',
    //         'tempat_lahir.required' => 'Tempat lahir harus diisi',
    //         'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
    //         'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
    //         'alamat.required' => 'Alamat lahir harus diisi',
    //         'nama_orang_tua.required' => 'Nama Orangtua harus diisi',
    //         'no_hp.required' => 'No HP harus diisi',
    //     ];
    //  }
}
