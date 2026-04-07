<?php

namespace App\Http\Requests;

use App\Models\TahunAjaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePengampuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guru_id' => ['required', 'exists:gurus,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $tahunAjaran = TahunAjaran::current() ?? TahunAjaran::latest('id')->first();

                if (! $tahunAjaran) {
                    $validator->errors()->add('guru_id', 'Tahun ajaran aktif belum tersedia.');
                    return;
                }

                $exists = \App\Models\Pengampu::query()
                    ->where('guru_id', $this->guru_id)
                    ->where('mata_pelajaran_id', $this->mata_pelajaran_id)
                    ->where('kelas_id', $this->kelas_id)
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('guru_id', 'Penugasan guru untuk mapel, kelas, dan tahun ajaran tersebut sudah ada.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'guru_id' => 'guru',
            'mata_pelajaran_id' => 'mata pelajaran',
            'kelas_id' => 'kelas',
        ];
    }
}
