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
            $table->time('jam_buka')->nullable()->after('status_operasional');
            $table->time('jam_tutup')->nullable()->after('jam_buka');
            $table->boolean('is_24_jam')->default(false)->after('jam_tutup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->dropColumn(['jam_buka', 'jam_tutup', 'is_24_jam']);
        });
    }
};
