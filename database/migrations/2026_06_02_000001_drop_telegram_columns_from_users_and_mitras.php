<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus kolom telegram_chat_id dan telegram_verification_code
     * dari tabel users dan mitras karena fitur Telegram digantikan
     * oleh sistem reset password via email.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_chat_id', 'telegram_verification_code']);
        });

        Schema::table('mitras', function (Blueprint $table) {
            $table->dropColumn(['telegram_chat_id', 'telegram_verification_code']);
        });
    }

    /**
     * Rollback: tambahkan kembali kolom jika migration dibalik.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_chat_id')->nullable();
            $table->string('telegram_verification_code')->nullable();
        });

        Schema::table('mitras', function (Blueprint $table) {
            $table->string('telegram_chat_id')->nullable();
            $table->string('telegram_verification_code')->nullable();
        });
    }
};
