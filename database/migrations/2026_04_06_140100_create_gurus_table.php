<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('legacy_wali_kelas_id')->nullable()->unique();
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->string('alamat');
            $table->timestamps();
        });

        if (Schema::hasTable('wali_kelas')) {
            $waliKelas = DB::table('wali_kelas')->get();

            foreach ($waliKelas as $row) {
                DB::table('gurus')->updateOrInsert(
                    ['legacy_wali_kelas_id' => $row->id],
                    [
                        'user_id' => $row->user_id,
                        'nip' => $row->nip,
                        'nama' => $row->nama,
                        'jenis_kelamin' => $row->jenis_kelamin,
                        'tempat_lahir' => $row->tempat_lahir,
                        'tanggal_lahir' => $row->tanggal_lahir,
                        'alamat' => $row->alamat,
                        'created_at' => $row->created_at,
                        'updated_at' => $row->updated_at,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
