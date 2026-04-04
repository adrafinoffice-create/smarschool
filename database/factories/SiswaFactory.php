<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Siswa;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    public function definition(): array
    {
        $kelasIds = Kelas::pluck('id')->toArray();

        if (empty($kelasIds)) {
            throw new Exception('Tidak ada kelas');
        }

        $nis = date('Y') . $this->faker->unique()->numerify('#####');

        return [
            'nis' => $nis,
            'nama' => $this->faker->name(),
            'kelas_id' => $this->faker->randomElement($kelasIds),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->dateTimeBetween('-15 years','-12 years')->format('Y-m-d'),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki','Perempuan']),
            'alamat' => $this->faker->address(),
            'nama_orang_tua' => $this->faker->name(),
            'no_hp' => $this->faker->phoneNumber(),
        ];
    }
}
