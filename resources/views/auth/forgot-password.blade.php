@extends('layouts.public')

@section('title', 'Reset Password — WanderMed')

@push('styles')
<style>
/* =========================================================
 * RESET PASSWORD — OTP FLOW — Light/Dark Adaptive
 * ========================================================= */
:root {
    --rp-card-bg:      rgba(255,255,255,0.05);
    --rp-card-border:  rgba(255,255,255,0.12);
    --rp-title:        #ffffff;
    --rp-subtitle:     rgba(255,255,255,0.55);
    --rp-label:        rgba(255,255,255,0.70);
    --rp-input-bg:     rgba(255,255,255,0.07);
    --rp-input-border: rgba(255,255,255,0.15);
    --rp-input-color:  #ffffff;
    --rp-placeholder:  rgba(255,255,255,0.35);
    --rp-eye-color:    rgba(255,255,255,0.40);
    --rp-eye-hover:    rgba(255,255,255,0.80);
    --rp-bar-bg:       rgba(255,255,255,0.10);
    --rp-back-color:   rgba(255,255,255,0.50);
    --rp-badge-bg:     rgba(255,122,0,0.12);
    --rp-badge-border: rgba(255,122,0,0.25);
    --rp-divider:      rgba(255,255,255,0.10);
    --rp-otp-bg:       rgba(255,255,255,0.07);
    --rp-otp-border:   rgba(255,255,255,0.18);
    --rp-otp-focus:    var(--hnb-orange);
    --rp-otp-color:    #ffffff;
    --rp-err-bg:       rgba(220,53,69,0.15);
    --rp-err-border:   rgba(220,53,69,0.35);
    --rp-err-txt:      #ffb3bb;
    --rp-ok-bg:        rgba(40,167,69,0.15);
    --rp-ok-border:    rgba(40,167,69,0.35);
    --rp-ok-txt:       #a8f5b8;
}
html.light-mode {
    --rp-card-bg:      rgba(255,255,255,0.90);
    --rp-card-border:  rgba(0,0,0,0.08);
    --rp-title:        #1a2a44;
    --rp-subtitle:     #6b7280;
    --rp-label:        #4a5568;
    --rp-input-bg:     #f7f9fc;
    --rp-input-border: #d1d5db;
    --rp-input-color:  #1a2a44;
    --rp-placeholder:  rgba(26,42,68,0.35);
    --rp-eye-color:    #9ca3af;
    --rp-eye-hover:    #4a5568;
    --rp-bar-bg:       rgba(0,0,0,0.08);
    --rp-back-color:   #6b7280;
    --rp-badge-bg:     rgba(255,122,0,0.08);
    --rp-badge-border: rgba(255,122,0,0.22);
    --rp-divider:      rgba(0,0,0,0.08);
    --rp-otp-bg:       #f0f4f9;
    --rp-otp-border:   #d1d5db;
    --rp-otp-focus:    var(--hnb-orange);
    --rp-otp-color:    #1a2a44;
    --rp-err-bg:       rgba(220,53,69,0.08);
    --rp-err-border:   rgba(220,53,69,0.28);
    --rp-err-txt:      #842029;
    --rp-ok-bg:        rgba(40,167,69,0.08);
    --rp-ok-border:    rgba(40,167,69,0.28);
    --rp-ok-txt:       #155724;
}

.rp-section {
    min-height: calc(100vh - 80px);
    display: flex; align-items: center; justify-content: center;
    padding: 110px 16px 80px;
}

/* ── Card ── */
.rp-card {
    background: var(--rp-card-bg);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--rp-card-border);
    border-radius: 22px;
    padding: 48px 44px;
    width: 100%; max-width: 480px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.22);
}
html.light-mode .rp-card { box-shadow: 0 12px 40px rgba(0,0,0,0.10); }

/* ── Step wrapper ── */
.rp-step { width: 100%; }
.rp-step-hidden {
    display: none;
}

/* ── Icon ── */
.rp-icon-wrap {
    width: 72px; height: 72px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 22px; font-size: 1.8rem; color: #fff;
}
.rp-icon-step1 {
    background: linear-gradient(135deg, var(--hnb-orange) 0%, #ff9f00 100%);
    box-shadow: 0 8px 24px rgba(255,122,0,0.40);
}
.rp-icon-step2 {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 8px 24px rgba(40,167,69,0.40);
}

/* ── Typography ── */
.rp-title {
    font-size: 1.5rem; font-weight: 700; color: var(--rp-title);
    text-align: center; margin-bottom: 8px; transition: color 0.3s;
}
.rp-subtitle {
    color: var(--rp-subtitle); text-align: center;
    font-size: 0.875rem; line-height: 1.65;
    margin-bottom: 28px; transition: color 0.3s;
}
.rp-label {
    display: block; color: var(--rp-label);
    font-size: 0.78rem; font-weight: 600;
    letter-spacing: 0.6px; text-transform: uppercase;
    margin-bottom: 8px; margin-top: 16px; transition: color 0.3s;
}

/* ── Input ── */
.rp-input-wrap { position: relative; }
.rp-input {
    width: 100%; background: var(--rp-input-bg);
    border: 1.5px solid var(--rp-input-border);
    border-radius: 10px; padding: 13px 46px 13px 16px;
    color: var(--rp-input-color); font-size: 0.95rem;
    outline: none; box-sizing: border-box;
    transition: border-color 0.25s, box-shadow 0.25s, background 0.3s, color 0.3s;
}
.rp-input::placeholder { color: var(--rp-placeholder); }
.rp-input:focus {
    border-color: var(--hnb-orange);
    box-shadow: 0 0 0 3px rgba(255,122,0,0.18);
}
.rp-input.is-invalid { border-color: #dc3545 !important; box-shadow: 0 0 0 3px rgba(220,53,69,0.18) !important; }
.rp-input.is-valid   { border-color: #28a745 !important; box-shadow: 0 0 0 3px rgba(40,167,69,0.18) !important; }
.rp-eye {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: var(--rp-eye-color);
    cursor: pointer; font-size: 1rem; transition: color 0.2s; padding: 0;
}
.rp-eye:hover { color: var(--rp-eye-hover); }

/* ── OTP Boxes ── */
.rp-otp-group {
    display: flex; gap: 10px; justify-content: center;
    margin-top: 4px;
}
.rp-otp-box {
    width: 58px; height: 68px;
    background: var(--rp-otp-bg);
    border: 2px solid var(--rp-otp-border);
    border-radius: 12px;
    text-align: center; font-size: 1.7rem; font-weight: 700;
    color: var(--rp-otp-color); outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.3s, color 0.3s;
    caret-color: var(--hnb-orange);
}
.rp-otp-box:focus {
    border-color: var(--rp-otp-focus);
    box-shadow: 0 0 0 3px rgba(255,122,0,0.22);
    background: var(--rp-input-bg);
}
.rp-otp-box.filled { border-color: var(--hnb-orange); color: var(--rp-otp-color); }

/* ── Email badge ── */
.rp-badge {
    background: var(--rp-badge-bg); border: 1px solid var(--rp-badge-border);
    border-radius: 8px; padding: 9px 16px;
    color: var(--hnb-orange); font-weight: 600; font-size: 0.875rem;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin-bottom: 22px; word-break: break-all; transition: background 0.3s;
}

/* ── Strength ── */
.rp-strength { display: flex; gap: 5px; margin-top: 8px; }
.rp-strength-bar { flex: 1; height: 3px; border-radius: 2px; background: var(--rp-bar-bg); transition: background 0.35s; }
.rp-stext { font-size: 0.74rem; margin-top: 5px; min-height: 16px; color: var(--rp-subtitle); }
.rp-mtext { font-size: 0.74rem; margin-top: 5px; min-height: 16px; }

/* ── Buttons ── */
.rp-btn {
    display: block; width: 100%; padding: 14px;
    color: #fff; font-weight: 700; font-size: 1rem;
    border: none; border-radius: 10px; cursor: pointer;
    transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
    margin-top: 24px; letter-spacing: 0.3px;
}
.rp-btn-orange {
    background: linear-gradient(135deg, var(--hnb-orange) 0%, #ff9f00 100%);
    box-shadow: 0 6px 20px rgba(255,122,0,0.35);
}
.rp-btn-orange:hover { opacity: 0.92; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(255,122,0,0.45); }
.rp-btn-green {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 6px 20px rgba(40,167,69,0.35);
}
.rp-btn-green:hover { opacity: 0.92; transform: translateY(-2px); box-shadow: 0 10px 28px rgba(40,167,69,0.45); }
.rp-btn:active { transform: translateY(0); }
.rp-btn:disabled { opacity: 0.60; cursor: not-allowed; transform: none !important; }

/* ── Back link ── */
.rp-back { text-align: center; margin-top: 18px; }
.rp-back a {
    color: var(--rp-back-color); font-size: 0.875rem;
    text-decoration: none; transition: color 0.2s;
}
.rp-back a:hover { color: var(--hnb-orange); }

/* ── Alert boxes ── */
.rp-alert {
    border-radius: 10px; padding: 12px 16px; font-size: 0.875rem;
    margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px;
    transition: background 0.3s, border-color 0.3s, color 0.3s;
}
.rp-alert-err { background: var(--rp-err-bg); border: 1px solid var(--rp-err-border); color: var(--rp-err-txt); }
.rp-alert-ok  { background: var(--rp-ok-bg);  border: 1px solid var(--rp-ok-border);  color: var(--rp-ok-txt); }

/* ── Divider ── */
.rp-divider { border: none; border-top: 1px solid var(--rp-divider); margin: 20px 0; }

/* ── Resend countdown ── */
.rp-resend { text-align: center; font-size: 0.82rem; color: var(--rp-subtitle); margin-top: 14px; }
.rp-resend a, .rp-resend button {
    background: none; border: none; padding: 0;
    color: var(--hnb-orange); cursor: pointer; font-size: 0.82rem;
    text-decoration: underline; transition: opacity 0.2s;
}
.rp-resend a:hover, .rp-resend button:hover { opacity: 0.75; }

@media (max-width: 480px) {
    .rp-card { padding: 36px 20px; }
    .rp-otp-box { width: 44px; height: 56px; font-size: 1.4rem; border-radius: 8px; }
    .rp-otp-group { gap: 6px; }
}
</style>
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
                    Berlaku selama <strong style="color:var(--hnb-orange);">15 menit</strong>.
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
/* ─── Step 1: Loading state on submit ─── */
document.getElementById('form-step1').addEventListener('submit', function () {
    const btn = document.getElementById('btn-step1');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim OTP...';
});

/* ─── OTP Box Navigation ─── */
const otpBoxes = document.querySelectorAll('.rp-otp-box');
const otpHidden = document.getElementById('otp-value');

otpBoxes.forEach((box, idx) => {
    box.addEventListener('input', function (e) {
        const val = this.value.replace(/\D/g, '');
        this.value = val.slice(-1);
        if (val && idx < otpBoxes.length - 1) otpBoxes[idx + 1].focus();
        if (this.value) this.classList.add('filled');
        else this.classList.remove('filled');
        syncOtp();
    });

    box.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && !this.value && idx > 0) {
            otpBoxes[idx - 1].focus();
            otpBoxes[idx - 1].value = '';
            otpBoxes[idx - 1].classList.remove('filled');
            syncOtp();
        }
        // Allow paste on first box
        if (e.key === 'ArrowLeft' && idx > 0) otpBoxes[idx - 1].focus();
        if (e.key === 'ArrowRight' && idx < otpBoxes.length - 1) otpBoxes[idx + 1].focus();
    });

    // Handle paste (e.g. from email copy)
    box.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        if (!pasted) return;
        otpBoxes.forEach((b, i) => {
            b.value = pasted[i] || '';
            if (b.value) b.classList.add('filled'); else b.classList.remove('filled');
        });
        const last = Math.min(pasted.length, otpBoxes.length) - 1;
        otpBoxes[last].focus();
        syncOtp();
    });
});

function syncOtp() {
    otpHidden.value = Array.from(otpBoxes).map(b => b.value).join('');
}

/* ─── Password Strength ─── */
const pwIn   = document.getElementById('rp-pw');
const cfIn   = document.getElementById('rp-cf');
const stext  = document.getElementById('stext');
const mtext  = document.getElementById('mtext');
const bars   = [1,2,3,4].map(n => document.getElementById('b'+n));
const C      = { 0:'transparent', 1:'#dc3545', 2:'#ff7a00', 3:'#ffc107', 4:'#28a745' };
const L      = { 0:'', 1:'Sangat lemah', 2:'Lemah', 3:'Cukup kuat', 4:'Kuat 💪' };

function strength(p) {
    if (!p) return 0;
    let s = 0;
    if (p.length >= 8) s++;
    if (p.length >= 12) s++;
    if (/[A-Z]/.test(p) && /[a-z]/.test(p)) s++;
    if (/[0-9]/.test(p) && /[^A-Za-z0-9]/.test(p)) s++;
    return Math.min(s, 4);
}

pwIn.addEventListener('input', function () {
    const sc = strength(this.value);
    bars.forEach((b, i) => { b.style.background = i < sc ? C[sc] : ''; });
    stext.textContent = L[sc]; stext.style.color = C[sc] || '';
    checkMatch();
});
cfIn.addEventListener('input', checkMatch);

function checkMatch() {
    if (!cfIn.value) { mtext.innerHTML = '&nbsp;'; return; }
    if (pwIn.value === cfIn.value) {
        mtext.textContent = '✅ Password cocok'; mtext.style.color = '#28a745';
        cfIn.classList.remove('is-invalid'); cfIn.classList.add('is-valid');
    } else {
        mtext.textContent = '❌ Password tidak cocok'; mtext.style.color = '#dc3545';
        cfIn.classList.add('is-invalid'); cfIn.classList.remove('is-valid');
    }
}

/* ─── Step 2: Validate before submit ─── */
document.getElementById('form-step2').addEventListener('submit', function (e) {
    syncOtp();
    if (otpHidden.value.length < 6) {
        e.preventDefault();
        otpBoxes[0].focus();
        return;
    }
    if (pwIn.value !== cfIn.value) {
        e.preventDefault();
        cfIn.classList.add('is-invalid');
        mtext.textContent = '❌ Password tidak cocok'; mtext.style.color = '#dc3545';
        return;
    }
    const btn = document.getElementById('btn-step2');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
});

/* ─── Show/hide password ─── */
function togglePw(id, btn) {
    const inp  = document.getElementById(id);
    const icon = btn.querySelector('i');
    inp.type   = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye'); icon.classList.toggle('fa-eye-slash');
}

/* ─── Auto-focus first OTP box if on step 2 ─── */
@if(session('otp_sent'))
    (function() {
        const firstEmpty = Array.from(otpBoxes).find(b => !b.value);
        if (firstEmpty) firstEmpty.focus();
    })();
@endif
</script>
@endpush
