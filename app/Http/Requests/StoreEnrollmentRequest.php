<?php

namespace App\Http\Requests;

use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreEnrollmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kelas_id' => ['required', 'exists:kelas,id'],
            'siswa_id' => ['required', 'array', 'min:1'],
            'siswa_id.*' => ['exists:siswa,id'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $tahunAjaran = TahunAjaran::current() ?? TahunAjaran::latest('id')->first();

                if (! $tahunAjaran) {
                    $validator->errors()->add('kelas_id', 'Tahun ajaran aktif belum tersedia.');
                    return;
                }

                $selectedSiswaIds = collect($this->input('siswa_id', []))
                    ->filter()
                    ->unique()
                    ->values();

                if ($selectedSiswaIds->isEmpty()) {
                    return;
                }

                $sudahTerdaftar = KelasSiswa::with(['siswa', 'kelas'])
                    ->where('tahun_ajaran_id', $tahunAjaran->id)
                    ->where('status', 'aktif')
                    ->whereIn('siswa_id', $selectedSiswaIds)
                    ->get();

                if ($sudahTerdaftar->isNotEmpty()) {
                    $pesan = $sudahTerdaftar
                        ->map(function ($item) {
                            $namaSiswa = $item->siswa?->nama ?? 'Siswa';
                            $namaKelas = $item->kelas?->nama_kelas ?? 'kelas lain';

                            return "{$namaSiswa} sudah aktif di {$namaKelas}. Hapus enrollment lama terlebih dahulu.";
                        })
                        ->implode(' ');

                    $validator->errors()->add('siswa_id', $pesan);
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'array' => ':attribute harus berupa daftar.',
            'min' => 'Minimal pilih :min siswa.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kelas_id' => 'kelas',
            'siswa_id' => 'siswa',
            'siswa_id.*' => 'siswa',
        ];
    }
}
