<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('wali_guru_id')->nullable()->after('wali_kelas_id')->constrained('gurus')->nullOnDelete();
        });

        $kelas = DB::table('kelas')->get();

        foreach ($kelas as $row) {
            $guruId = DB::table('gurus')
                ->where('legacy_wali_kelas_id', $row->wali_kelas_id)
                ->value('id');

            if ($guruId) {
                DB::table('kelas')->where('id', $row->id)->update(['wali_guru_id' => $guruId]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wali_guru_id');
        });
    }
};
