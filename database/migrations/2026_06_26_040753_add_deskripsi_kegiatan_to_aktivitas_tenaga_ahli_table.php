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
    Schema::table('aktivitas_tenaga_ahli', function (Blueprint $table) {
        $table->longText('deskripsi_kegiatan')->nullable()->after('nama_kegiatan');
    });
}

public function down(): void
{
    Schema::table('aktivitas_tenaga_ahli', function (Blueprint $table) {
        $table->dropColumn('deskripsi_kegiatan');
    });
}

};
