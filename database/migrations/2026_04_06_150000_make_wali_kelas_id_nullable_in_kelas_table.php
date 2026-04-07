<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['wali_kelas_id']);
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('wali_kelas_id')->nullable()->change();
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign('wali_kelas_id')
                ->references('id')
                ->on('wali_kelas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['wali_kelas_id']);
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('wali_kelas_id')->nullable(false)->change();
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->foreign('wali_kelas_id')
                ->references('id')
                ->on('wali_kelas')
                ->cascadeOnDelete();
        });
    }
};
