<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Siswa::factory(100)->create();

        // Pengaturan::create([
        //     'nama_sekolah' => 'SMP Negeri 1 saliwu',
        //     'alamat' => 'saliwu',
        //     'jam_masuk' => '07.00:00',
        //     'jam_pulang' => '12.00:00',
        // ]);

        // User::factory()->create([
        //     'name' =>'admin sekolah',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('admin123'),
        // ]);


        //  User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => Hash::make('admin123'),
        //     'role' => 'admin',
        // ]);


        // User::create([
        //     'name' => 'Guru',
        //     'email' => 'guru@gmail.com',
        //     'password' => Hash::make('guru123123'),
        //     'role' => 'guru',
        // ]);



    }
}
