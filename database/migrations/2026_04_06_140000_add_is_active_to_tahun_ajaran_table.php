<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('semester');
        });

        $latestId = DB::table('tahun_ajaran')->max('id');

        if ($latestId) {
            DB::table('tahun_ajaran')->where('id', $latestId)->update(['is_active' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
