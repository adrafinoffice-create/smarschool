<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas; // Tambahkan ini agar model Kelas dikenali
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat data Pengaturan Sekolah
        Pengaturan::create([
            'nama_sekolah' => 'SMP Negeri 1 Saliwu',
            'alamat'       => 'Saliwu',
            'jam_masuk'    => '07:00:00',
            'jam_pulang'   => '12:00:00',
        ]);

        // 2. Buat data User: Admin
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // 3. Buat data User: Guru
        $guruUser = User::create([
            'name'     => 'Guru',
            'email'    => 'guru@gmail.com',
            'password' => Hash::make('guru123123'),
            'role'     => 'guru',
        ]);

        // 4. Buat data Tahun Ajaran
        $tahunAjaran = \App\Models\TahunAjaran::create([
            'tahun_ajaran' => '2023/2024',
        ]);

        // 5. Buat data Wali Kelas
        $waliKelas = \App\Models\WaliKelas::create([
            'user_id' => $guruUser->id,
            'nip' => '1234567890',
            'nama' => 'Guru Wali Kelas',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1980-01-01',
            'alamat' => 'Jakarta Selatan',
        ]);

        // 6. Buat data Kelas
        $kelas = Kelas::create([
            'nama_kelas' => 'VII-A',
            'tahun_ajaran_id' => $tahunAjaran->id,
            'wali_kelas_id' => $waliKelas->id,
        ]);

        // 7. Baru Generate 100 data Siswa
        Siswa::factory(100)->create();
    }
}
