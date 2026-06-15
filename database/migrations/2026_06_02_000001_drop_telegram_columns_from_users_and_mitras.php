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
        if (Illuminate\Support\Facades\DB::getDriverName() === 'sqlite') {
            Illuminate\Support\Facades\DB::statement('DROP INDEX IF EXISTS users_telegram_chat_id_unique');
            Illuminate\Support\Facades\DB::statement('DROP INDEX IF EXISTS users_telegram_verification_code_unique');
            Illuminate\Support\Facades\DB::statement('DROP INDEX IF EXISTS mitras_telegram_chat_id_unique');
            Illuminate\Support\Facades\DB::statement('DROP INDEX IF EXISTS mitras_telegram_verification_code_unique');
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['telegram_chat_id']);
                $table->dropUnique(['telegram_verification_code']);
            });

            Schema::table('mitras', function (Blueprint $table) {
                $table->dropUnique(['telegram_chat_id']);
                $table->dropUnique(['telegram_verification_code']);
            });
        }

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
