/* =========================================================
 * FILE: public/js/forgot-password.js
 * THEME: Client-Side Logic for Reset Password OTP Flow
 * ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const formStep1 = document.getElementById('form-step1');
    if (formStep1) {
        formStep1.addEventListener('submit', function () {
            const btn = document.getElementById('btn-step1');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim OTP...';
            }
        });
    }

    /* ─── OTP Box Navigation ─── */
    const otpBoxes = document.querySelectorAll('.rp-otp-box');
    const otpHidden = document.getElementById('otp-value');

    if (otpBoxes.length > 0) {
        otpBoxes.forEach((box, idx) => {
            box.addEventListener('input', function () {
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
                if (e.key === 'ArrowLeft' && idx > 0) otpBoxes[idx - 1].focus();
                if (e.key === 'ArrowRight' && idx < otpBoxes.length - 1) otpBoxes[idx + 1].focus();
            });

            box.addEventListener('paste', function (e) {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                if (!pasted) return;
                otpBoxes.forEach((b, i) => {
                    b.value = pasted[i] || '';
                    if (b.value) b.classList.add('filled');
                    else b.classList.remove('filled');
                });
                const last = Math.min(pasted.length, otpBoxes.length) - 1;
                if (otpBoxes[last]) otpBoxes[last].focus();
                syncOtp();
            });
        });
    }

    function syncOtp() {
        if (otpHidden) {
            otpHidden.value = Array.from(otpBoxes).map(b => b.value).join('');
        }
    }

    /* ─── Password Strength ─── */
    const pwIn = document.getElementById('rp-pw');
    const cfIn = document.getElementById('rp-cf');
    const stext = document.getElementById('stext');
    const mtext = document.getElementById('mtext');
    const bars = [1, 2, 3, 4].map(n => document.getElementById('b' + n));
    const C = { 0: 'transparent', 1: '#dc3545', 2: '#ff7a00', 3: '#ffc107', 4: '#28a745' };
    const L = { 0: '', 1: 'Sangat lemah', 2: 'Lemah', 3: 'Cukup kuat', 4: 'Kuat 💪' };

    function strength(p) {
        if (!p) return 0;
        let s = 0;
        if (p.length >= 8) s++;
        if (p.length >= 12) s++;
        if (/[A-Z]/.test(p) && /[a-z]/.test(p)) s++;
        if (/[0-9]/.test(p) && /[^A-Za-z0-9]/.test(p)) s++;
        return Math.min(s, 4);
    }

    if (pwIn) {
        pwIn.addEventListener('input', function () {
            const sc = strength(this.value);
            bars.forEach((b, i) => {
                if (b) b.style.background = i < sc ? C[sc] : '';
            });
            if (stext) {
                stext.textContent = L[sc];
                stext.style.color = C[sc] || '';
            }
            checkMatch();
        });
    }

    if (cfIn) {
        cfIn.addEventListener('input', checkMatch);
    }

    function checkMatch() {
        if (!cfIn || !pwIn || !mtext) return;
        if (!cfIn.value) {
            mtext.innerHTML = '&nbsp;';
            return;
        }
        if (pwIn.value === cfIn.value) {
            mtext.textContent = '✅ Password cocok';
            mtext.style.color = '#28a745';
            cfIn.classList.remove('is-invalid');
            cfIn.classList.add('is-valid');
        } else {
            mtext.textContent = '❌ Password tidak cocok';
            mtext.style.color = '#dc3545';
            cfIn.classList.add('is-invalid');
            cfIn.classList.remove('is-valid');
        }
    }

    /* ─── Step 2: Validate before submit ─── */
    const formStep2 = document.getElementById('form-step2');
    if (formStep2) {
        formStep2.addEventListener('submit', function (e) {
            syncOtp();
            if (otpHidden && otpHidden.value.length < 6) {
                e.preventDefault();
                if (otpBoxes[0]) otpBoxes[0].focus();
                return;
            }
            if (pwIn && cfIn && pwIn.value !== cfIn.value) {
                e.preventDefault();
                cfIn.classList.add('is-invalid');
                if (mtext) {
                    mtext.textContent = '❌ Password tidak cocok';
                    mtext.style.color = '#dc3545';
                }
                return;
            }
            const btn = document.getElementById('btn-step2');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            }
        });
    }

    /* ─── Show/hide password ─── */
    window.togglePw = function (id, btn) {
        const inp = document.getElementById(id);
        if (!inp || !btn) return;
        const icon = btn.querySelector('i');
        inp.type = inp.type === 'password' ? 'text' : 'password';
        if (icon) {
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    };

    /* ─── Auto-focus first OTP box if on step 2 ─── */
    if (window.ForgotPasswordConfig && window.ForgotPasswordConfig.otpSent) {
        const firstEmpty = Array.from(otpBoxes).find(b => !b.value);
        if (firstEmpty) firstEmpty.focus();
    }
});
