<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    /**
     * Handle incoming webhook requests from Telegram.
     */
    public function handleWebhook(Request $request)
    {
        $update = $request->all();
        
        Log::info('Telegram Webhook received:', $update);

        if (!isset($update['message'])) {
            return response()->json(['status' => 'ignored']);
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'] ?? null;
        $text = trim($message['text'] ?? '');

        if (!$chatId || empty($text)) {
            return response()->json(['status' => 'empty']);
        }

        // Handle commands
        if (str_starts_with($text, '/start')) {
            $this->handleStartCommand($chatId, $text);
        } elseif (str_starts_with($text, '/reset')) {
            $this->handleResetCommand($chatId, $text);
        } else {
            $this->sendTelegramMessage($chatId, "Perintah tidak dikenali. 🤖\n\nKetik /start untuk bantuan atau /reset <email_anda> untuk mereset kata sandi.");
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Set the Webhook URL on Telegram.
     */
    public function setWebhook(Request $request)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            return response()->json(['error' => 'TELEGRAM_BOT_TOKEN not configured in .env'], 500);
        }

        // Webhook URL needs to be HTTPS. If APP_URL is localhost, user will need ngrok.
        $appUrl = secure_url('/telegram/webhook');
        
        $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/setWebhook", [
            'url' => $appUrl
        ]);

        return response()->json($response->json());
    }

    /**
     * Handle /start command.
     */
    protected function handleStartCommand($chatId, $text)
    {
        $parts = explode(' ', $text);
        
        // If it has a code parameter: /start WDM-XXXXXX
        if (count($parts) > 1 && str_starts_with($parts[1], 'WDM-')) {
            $code = trim($parts[1]);
            
            $user = User::where('telegram_verification_code', $code)->first();
            $mitra = null;
            if (!$user) {
                $mitra = Mitra::where('telegram_verification_code', $code)->first();
            }

            $account = $user ?: $mitra;
            
            if ($account) {
                // Link telegram account
                $account->telegram_chat_id = $chatId;
                $account->telegram_verification_code = null;
                $account->save();

                $name = $user ? $user->name : $mitra->nama_penanggung_jawab;
                $msg = "Halo {$name}! Akun WanderMed Anda berhasil dihubungkan dengan Telegram! 🎉\n\n" .
                       "Sekarang Anda dapat memulihkan kata sandi secara instan lewat chat ini dengan mengirimkan perintah:\n" .
                       "`/reset {$account->email}`";
                $this->sendTelegramMessage($chatId, $msg);
            } else {
                $this->sendTelegramMessage($chatId, "❌ Kode verifikasi salah, kedaluwarsa, atau sudah digunakan. Silakan periksa kembali di dashboard Anda.");
            }
        } else {
            // General /start instruction
            $msg = "Selamat datang di WanderMed Recovery Bot! 🏥\n\n" .
                   "Untuk menghubungkan akun Anda:\n" .
                   "1. Masuk ke Dashboard WanderMed Anda (Wisatawan atau Faskes).\n" .
                   "2. Buka Pengaturan Akun -> Integrasi Telegram.\n" .
                   "3. Dapatkan Kode Verifikasi Anda.\n" .
                   "4. Kirim pesan ke bot ini: `/start <KODE_VERIFIKASI>`\n\n" .
                   "Jika sudah terhubung, Anda dapat mereset kata sandi kapan saja dengan mengirim:\n" .
                   "`/reset <email_anda>`";
            $this->sendTelegramMessage($chatId, $msg);
        }
    }

    /**
     * Handle /reset command.
     */
    protected function handleResetCommand($chatId, $text)
    {
        $parts = explode(' ', $text);
        if (count($parts) < 2) {
            $this->sendTelegramMessage($chatId, "⚠️ Format salah.\nGunakan: `/reset <email_anda>`\n\nContoh: `/reset budi@gmail.com`");
            return;
        }

        $email = trim($parts[1]);
        
        $user = User::where('email', $email)->first();
        $mitra = null;
        if (!$user) {
            $mitra = Mitra::where('email', $email)->first();
        }

        $account = $user ?: $mitra;

        if (!$account) {
            $this->sendTelegramMessage($chatId, "❌ Email tidak ditemukan dalam sistem WanderMed.");
            return;
        }

        if (empty($account->telegram_chat_id)) {
            $this->sendTelegramMessage($chatId, "❌ Akun WanderMed Anda belum terhubung ke Telegram. Silakan login ke web, buka Dashboard, lalu hubungkan Telegram di menu Pengaturan Akun.");
            return;
        }

        // Verify if the telegram sender is the linked user
        if ($account->telegram_chat_id != $chatId) {
            $this->sendTelegramMessage($chatId, "❌ Akun Telegram ini tidak terhubung ke email yang Anda masukkan.");
            return;
        }

        // Generate temporary password
        $tempPassword = 'WDM-' . Str::upper(Str::random(6));
        $account->password = Hash::make($tempPassword);
        $account->save();

        $msg = "🔐 Kata sandi Anda berhasil disetel ulang!\n\n" .
               "Kata sandi sementara Anda:\n`{$tempPassword}`\n\n" .
               "Silakan masuk ke web WanderMed menggunakan kata sandi sementara ini, lalu segera ubah kata sandi Anda di menu Pengaturan Akun.";
        $this->sendTelegramMessage($chatId, $msg);
    }

    /**
     * Send message helper.
     */
    protected function sendTelegramMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            Log::error('TELEGRAM_BOT_TOKEN is not configured.');
            return;
        }

        Http::withoutVerifying()->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown'
        ]);
    }
}
