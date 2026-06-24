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
        // 1. Create aktivitas_tenaga_ahli
        Schema::create('aktivitas_tenaga_ahli', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenaga_ahli_id');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('nama_kegiatan');
            $table->string('kategori');
            $table->string('lokasi');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->string('dokumentasi_foto')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamps();

            $table->foreign('tenaga_ahli_id')->references('id')->on('tenaga_ahli')->onDelete('cascade');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('cascade');
        });

        // 2. Create aktivitas_staf_administrasi
        Schema::create('aktivitas_staf_administrasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staf_administrasi_id');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('nama_kegiatan');
            $table->string('kategori');
            $table->string('lokasi');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->string('dokumentasi_foto')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('dibuat_oleh');
            $table->timestamps();

            $table->foreign('staf_administrasi_id')->references('id')->on('staf_administrasi')->onDelete('cascade');
            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('cascade');
        });

        // 3. Drop jabatan from tenaga_ahli and staf_administrasi
        Schema::table('tenaga_ahli', function (Blueprint $table) {
            $table->dropColumn('jabatan');
        });
        Schema::table('staf_administrasi', function (Blueprint $table) {
            $table->dropColumn('jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staf_administrasi', function (Blueprint $table) {
            $table->string('jabatan', 100)->nullable();
        });
        Schema::table('tenaga_ahli', function (Blueprint $table) {
            $table->string('jabatan', 100)->nullable();
        });

        Schema::dropIfExists('aktivitas_staf_administrasi');
        Schema::dropIfExists('aktivitas_tenaga_ahli');
    }
};
