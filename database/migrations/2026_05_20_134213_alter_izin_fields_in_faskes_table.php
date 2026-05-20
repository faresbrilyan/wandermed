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
        Schema::table('faskes', function (Blueprint $table) {
            $table->string('nomor_izin_praktik')->nullable()->after('jenis_faskes');
            $table->string('foto_plang_izin_path')->nullable()->after('foto_path');
            $table->string('foto_kondisi_faskes_path')->nullable()->after('foto_plang_izin_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->dropColumn(['nomor_izin_praktik', 'foto_plang_izin_path', 'foto_kondisi_faskes_path']);
        });
    }
};
