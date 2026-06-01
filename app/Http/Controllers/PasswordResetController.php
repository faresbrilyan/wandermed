<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Mitra;
use Carbon\Carbon;

/**
 * PasswordResetController
 *
 * Alur reset password via OTP 6-digit yang dikirim ke email:
 *
 *  1. showForgotForm()  — tampilkan halaman dengan Step 1 (input email)
 *  2. sendOtp()         — validasi email, buat OTP 6-digit, kirim ke email,
 *                         redirect kembali ke halaman yang sama dengan
 *                         session('otp_sent') = true → tampil Step 2
 *  3. resetWithOtp()    — validasi OTP + password baru, simpan, hapus token
 */
class PasswordResetController extends Controller
{
    /**
     * Tampilkan halaman Lupa Password.
     * View akan menentukan sendiri apakah tampilkan Step 1 atau Step 2
     * berdasarkan keberadaan session('otp_sent').
     *
     * GET /password/forgot
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim OTP 6-digit ke email pengguna.
     *
     * POST /password/forgot
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($request->email));

        // Cari akun di tabel users (Wisatawan) atau mitras (Mitra Faskes)
        $user  = User::where('email', $email)->first();
        $mitra = !$user ? Mitra::where('email', $email)->first() : null;

        // Jika email tidak ditemukan — tetap redirect dengan session sukses
        // agar penyerang tidak bisa menebak email yang terdaftar
        if (!$user && !$mitra) {
            return back()
                ->withInput(['email' => $email])
                ->with('otp_sent', true)
                ->with('reset_email', $email);
        }

        // Buat OTP 6-digit acak
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hapus token lama (jika ada) lalu simpan yang baru
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => Hash::make($otp),   // simpan ter-hash
            'created_at' => Carbon::now(),
        ]);

        // Kirim email berisi OTP
        $name        = $user ? $user->name : $mitra->nama_faskes;
        $accountType = $user ? 'Wisatawan' : 'Mitra Faskes';

        Mail::send('emails.reset_otp', [
            'otp'         => $otp,
            'name'        => $name,
            'accountType' => $accountType,
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject('🔑 Kode OTP Reset Password WanderMed');
        });

        // Redirect kembali ke halaman yang sama dengan session
        // yang akan memicu tampilan Step 2
        return back()
            ->with('otp_sent', true)
            ->with('reset_email', $email);
    }

    /**
     * Verifikasi OTP + simpan password baru.
     *
     * POST /password/reset
     */
    public function resetWithOtp(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'otp'                   => 'required|string|size:6',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'otp.required'                   => 'Kode OTP wajib diisi.',
            'otp.size'                       => 'Kode OTP harus tepat 6 digit.',
            'password.required'              => 'Password baru wajib diisi.',
            'password.min'                   => 'Password minimal 8 karakter.',
            'password.confirmed'             => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $email = strtolower(trim($request->email));
        $otp   = $request->otp;

        // Cari token di database
        $record = DB::table('password_reset_tokens')
                    ->where('email', $email)
                    ->first();

        // Token tidak ditemukan
        if (!$record) {
            return back()
                ->with('otp_sent', true)
                ->with('reset_email', $email)
                ->withErrors(['otp' => 'Kode OTP tidak valid atau sudah digunakan. Silakan minta kode baru.']);
        }

        // Cek kadaluarsa (15 menit)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Kode OTP telah kadaluarsa (15 menit). Silakan minta kode baru.']);
        }

        // Verifikasi OTP
        if (!Hash::check($otp, $record->token)) {
            return back()
                ->with('otp_sent', true)
                ->with('reset_email', $email)
                ->withErrors(['otp' => 'Kode OTP salah. Periksa kembali email Anda.']);
        }

        // Update password di model yang sesuai
        $user  = User::where('email', $email)->first();
        $mitra = !$user ? Mitra::where('email', $email)->first() : null;

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        } elseif ($mitra) {
            $mitra->password = Hash::make($request->password);
            $mitra->save();
        } else {
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Email tidak ditemukan di sistem.']);
        }

        // Hapus OTP agar tidak bisa dipakai lagi
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')
            ->with('success', '✅ Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}
