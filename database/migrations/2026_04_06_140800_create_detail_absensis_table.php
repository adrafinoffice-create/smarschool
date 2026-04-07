<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_absensi_id')->constrained('sesi_absensis')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Alpa');
            $table->text('keterangan')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();

            $table->unique(['sesi_absensi_id', 'siswa_id'], 'detail_absensi_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_absensis');
    }
};
