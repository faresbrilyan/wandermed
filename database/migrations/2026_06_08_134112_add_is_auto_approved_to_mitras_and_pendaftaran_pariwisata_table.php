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
        Schema::table('mitras', function (Blueprint $table) {
            $table->boolean('is_auto_approved')->default(false)->after('is_verified');
        });

        Schema::table('pendaftaran_pariwisata', function (Blueprint $table) {
            $table->boolean('is_auto_approved')->default(false)->after('status_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn('is_auto_approved');
        });

        Schema::table('pendaftaran_pariwisata', function (Blueprint $table) {
            $table->dropColumn('is_auto_approved');
        });
    }
};

