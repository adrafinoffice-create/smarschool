<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->date('tanggal_masuk')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'kelas_id', 'tahun_ajaran_id'], 'kelas_siswa_unique');
        });

        $tahunAjaranId = DB::table('tahun_ajaran')->where('is_active', true)->value('id')
            ?? DB::table('tahun_ajaran')->max('id');

        if ($tahunAjaranId) {
            $siswas = DB::table('siswa')->get();

            foreach ($siswas as $siswa) {
                DB::table('kelas_siswa')->updateOrInsert(
                    [
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $siswa->kelas_id,
                        'tahun_ajaran_id' => $tahunAjaranId,
                    ],
                    [
                        'status' => 'aktif',
                        'tanggal_masuk' => now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_siswa');
    }
};
