<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('tenaga_ahli')) {
            Schema::table('tenaga_ahli', function (Blueprint $table) {
                $table->time('waktu')->after('jabatan')->default('08:00:00');
                $table->string('kategori')->after('waktu')->default('Lainnya');
            });
        }

        if (Schema::hasTable('staf_administrasi')) {
            Schema::table('staf_administrasi', function (Blueprint $table) {
                $table->time('waktu')->after('jabatan')->default('08:00:00');
                $table->string('kategori')->after('waktu')->default('Lainnya');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tenaga_ahli') && Schema::hasColumn('tenaga_ahli', 'waktu')) {
            Schema::table('tenaga_ahli', function (Blueprint $table) {
                $table->dropColumn(['waktu', 'kategori']);
            });
        }

        if (Schema::hasTable('staf_administrasi') && Schema::hasColumn('staf_administrasi', 'waktu')) {
            Schema::table('staf_administrasi', function (Blueprint $table) {
                $table->dropColumn(['waktu', 'kategori']);
            });
        }
    }
};
