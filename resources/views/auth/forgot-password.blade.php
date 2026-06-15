@extends('layouts.public')

@section('title', 'Reset Password — WanderMed')

@push('styles')
    <link href="{{ asset('css/forgot-password.css') }}" rel="stylesheet">
@endpush

@section('content')
    @include('partials.navbar')

    <section class="rp-section">
        <div class="rp-card">

            {{-- ======================================================
                 STEP 1 — Input Email
                 Ditampilkan secara default, disembunyikan setelah OTP dikirim
                 ====================================================== --}}
            <div class="rp-step" id="step-1" {{ session('otp_sent') ? 'style=display:none' : '' }}>

                <div class="rp-icon-wrap rp-icon-step1">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h1 class="rp-title">Lupa Password?</h1>
                <p class="rp-subtitle">
                    Masukkan email yang terdaftar di WanderMed.<br>
                    Kami akan mengirimkan kode OTP 6-digit ke inbox Anda.
                </p>

                @if(!session('otp_sent') && $errors->any())
                    <div class="rp-alert rp-alert-err">
                        <i class="fas fa-exclamation-circle mt-1"></i>
                        <span>{{ $errors->first('email') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" id="form-step1">
                    @csrf
                    <label for="fp-email" class="rp-label">Alamat Email</label>
                    <input
                        id="fp-email"
                        type="email"
                        name="email"
                        class="rp-input"
                        placeholder="contoh@email.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >

                    <button type="submit" class="rp-btn rp-btn-orange" id="btn-step1">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Kode OTP
                    </button>
                </form>

                <div class="rp-back">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                    </a>
                </div>

            </div>

            {{-- ======================================================
                 STEP 2 — Input OTP + Password Baru
                 Ditampilkan setelah OTP berhasil dikirim
                 ====================================================== --}}
            <div class="rp-step" id="step-2" {{ !session('otp_sent') ? 'style=display:none' : '' }}>

                <div class="rp-icon-wrap rp-icon-step2">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="rp-title">Masukkan Kode OTP</h1>
                <p class="rp-subtitle">
                    Kode 6-digit telah dikirim ke email Anda.<br>
                    Berlaku selama <strong style="color:var(--hnb-orange);">15 menit</strong>.<br>
                    <small style="opacity: 0.85; margin-top: 4px; display: inline-block;">
                        (Jika kode tidak muncul, silakan periksa folder <strong>Spam</strong> atau <strong>Promosi</strong> email Anda)
                    </small>
                </p>

                {{-- Email badge --}}
                @if(session('reset_email'))
                    <div class="rp-badge">
                        <i class="fas fa-envelope"></i>
                        <span>{{ session('reset_email') }}</span>
                    </div>
                @endif

                {{-- Error untuk OTP / password --}}
                @if(session('otp_sent') && $errors->any())
                    <div class="rp-alert rp-alert-err">
                        <i class="fas fa-exclamation-circle mt-1"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update.reset') }}" id="form-step2">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">

                    {{-- OTP Boxes --}}
                    <label class="rp-label" style="text-align:center; display:block;">Kode OTP</label>
                    <div class="rp-otp-group" id="otp-group">
                        @for($i = 0; $i < 6; $i++)
                            <input
                                type="text"
                                inputmode="numeric"
                                maxlength="1"
                                class="rp-otp-box"
                                data-index="{{ $i }}"
                                autocomplete="one-time-code"
                            >
                        @endfor
                    </div>
                    {{-- Hidden field untuk nilai OTP gabungan --}}
                    <input type="hidden" name="otp" id="otp-value">

                    <hr class="rp-divider">

                    {{-- Password Baru --}}
                    <label for="rp-pw" class="rp-label">Password Baru</label>
                    <div class="rp-input-wrap">
                        <input
                            id="rp-pw"
                            type="password"
                            name="password"
                            class="rp-input"
                            placeholder="Minimal 8 karakter"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="rp-eye" onclick="togglePw('rp-pw', this)" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="rp-strength" id="bars">
                        <div class="rp-strength-bar" id="b1"></div>
                        <div class="rp-strength-bar" id="b2"></div>
                        <div class="rp-strength-bar" id="b3"></div>
                        <div class="rp-strength-bar" id="b4"></div>
                    </div>
                    <div class="rp-stext" id="stext"></div>

                    {{-- Konfirmasi Password --}}
                    <label for="rp-cf" class="rp-label">Konfirmasi Password</label>
                    <div class="rp-input-wrap">
                        <input
                            id="rp-cf"
                            type="password"
                            name="password_confirmation"
                            class="rp-input"
                            placeholder="Ulangi password baru"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="rp-eye" onclick="togglePw('rp-cf', this)" tabindex="-1">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="rp-mtext" id="mtext">&nbsp;</div>

                    <button type="submit" class="rp-btn rp-btn-green" id="btn-step2">
                        <i class="fas fa-lock mr-2"></i>
                        Simpan Password Baru
                    </button>
                </form>

                <div class="rp-resend">
                    Belum terima kode?
                    <form method="POST" action="{{ route('password.email') }}" style="display:inline;" id="form-resend">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('reset_email') }}">
                        <button type="submit" id="btn-resend">Kirim Ulang</button>
                    </form>
                </div>

                <div class="rp-back" style="margin-top:10px;">
                    <a href="{{ route('password.forgot') }}">
                        <i class="fas fa-arrow-left mr-1"></i> Ganti Email
                    </a>
                </div>

            </div>

        </div>
    </section>

    <section class="bg-hnb-navy pt-5 pb-4">
        @include('partials.footer')
    </section>
@endsection

@push('scripts')
<script>
    window.ForgotPasswordConfig = {
        otpSent: @json(session('otp_sent') ? true : false)
    };
</script>
<script src="{{ asset('js/forgot-password.js') }}"></script>
@endpush

