async function forgotPassword(e) {
    e.preventDefault();
    
    const { value: formValues } = await Swal.fire({
        title: 'Reset Password via PIN',
        html: `
            <div style="text-align: left; margin-top: 10px;">
                <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Email Akun</label>
                <input id="swal-email" class="swal2-input" placeholder="contoh@gmail.com" style="width: 100%; margin: 0 0 15px 0; box-sizing: border-box;">
                
                <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">6-Digit PIN Pemulihan</label>
                <input id="swal-pin" class="swal2-input" placeholder="Misal: 849201" maxlength="6" style="width: 100%; margin: 0 0 15px 0; box-sizing: border-box;">
                
                <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Password Baru</label>
                <input id="swal-pass" type="password" class="swal2-input" placeholder="Minimal 8 karakter" style="width: 100%; margin: 0 0 15px 0; box-sizing: border-box;">
                
                <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Konfirmasi Password Baru</label>
                <input id="swal-pass-conf" type="password" class="swal2-input" placeholder="Ulangi password baru" style="width: 100%; margin: 0; box-sizing: border-box;">
            </div>
        `,
        background: '#111827',
        color: '#e8ecf4',
        showCancelButton: true,
        confirmButtonColor: '#ff7a00',
        cancelButtonColor: '#4b5563',
        confirmButtonText: '<i class="fas fa-check"></i> Reset Password',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const email = document.getElementById('swal-email').value;
            const pin = document.getElementById('swal-pin').value;
            const pass = document.getElementById('swal-pass').value;
            const pass_conf = document.getElementById('swal-pass-conf').value;

            if (!email || !pin || !pass || !pass_conf) {
                Swal.showValidationMessage('Semua kolom wajib diisi!');
                return false;
            }

            if (pass.length < 8) {
                Swal.showValidationMessage('Password baru minimal 8 karakter!');
                return false;
            }

            if (pass !== pass_conf) {
                Swal.showValidationMessage('Konfirmasi password tidak cocok!');
                return false;
            }

            return fetch(window.WanderMedConfig.resetPasswordPinUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.WanderMedConfig.csrfToken
                },
                body: JSON.stringify({ 
                    email: email, 
                    recovery_pin: pin,
                    password: pass,
                    password_confirmation: pass_conf
                })
            })
            .then(response => {
                if (!response.ok) throw new Error(response.statusText)
                return response.json()
            })
            .catch(error => {
                Swal.showValidationMessage(`Gagal menghubungi server: ${error}`)
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    });

    if (formValues) {
        if (formValues.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: formValues.message,
                icon: 'success',
                background: '#111827',
                color: '#e8ecf4',
                confirmButtonColor: '#1cc88a'
            });
        } else {
            Swal.fire({
                title: 'Gagal',
                text: formValues.message || 'Gagal mereset password.',
                icon: 'error',
                background: '#111827',
                color: '#e8ecf4',
                confirmButtonColor: '#ff7a00'
            });
        }
    }
}

async function requestDeleteAccount(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Lupa PIN Pemulihan?',
        html: `
            <p style="color:#94a3b8; font-size:13.5px; line-height:1.5; margin-bottom:20px;">
                Silakan pilih salah satu opsi di bawah ini untuk pemulihan atau penghapusan akun Anda:
            </p>
            <div style="display:flex; flex-direction:column; gap:12px;">
                <button id="btn-wa-reset" class="btn btn-success btn-block py-3" style="font-size: 14px; font-weight:600; border-radius:10px;">
                    <i class="fab fa-whatsapp" style="font-size:16px; margin-right:6px;"></i> Hubungi Admin via WhatsApp
                </button>
                <button id="btn-delete-request" class="btn btn-danger btn-block py-3" style="font-size: 14px; font-weight:600; border-radius:10px;">
                    <i class="fas fa-trash-alt" style="font-size:16px; margin-right:6px;"></i> Ajukan Hapus Akun
                </button>
            </div>
        `,
        background: '#111827',
        color: '#e8ecf4',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonColor: '#4b5563',
        cancelButtonText: 'Batal',
        didOpen: () => {
            const modal = Swal.getPopup();
            modal.querySelector('#btn-wa-reset').addEventListener('click', () => {
                Swal.close();
                triggerWaReset();
            });
            modal.querySelector('#btn-delete-request').addEventListener('click', () => {
                Swal.close();
                triggerDeleteAccountRequest();
            });
        }
    });
}

async function triggerWaReset() {
    const { value: formValues } = await Swal.fire({
        title: 'Form Reset Password via WA',
        html: `
            <div style="text-align: left; margin-top: 10px;">
                <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Nama Akun</label>
                <input id="swal-wa-name" class="swal2-input" placeholder="Masukkan nama akun Anda" style="width: 100%; margin: 0 0 15px 0; box-sizing: border-box;">
                
                <label style="color:#94a3b8; font-size:13px; margin-bottom:5px; display:block;">Email Akun</label>
                <input id="swal-wa-email" type="email" class="swal2-input" placeholder="Masukkan email terdaftar" style="width: 100%; margin: 0; box-sizing: border-box;">
            </div>
        `,
        background: '#111827',
        color: '#e8ecf4',
        showCancelButton: true,
        confirmButtonColor: '#1cc88a',
        cancelButtonColor: '#4b5563',
        confirmButtonText: '<i class="fab fa-whatsapp"></i> Kirim Chat WA',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const name = document.getElementById('swal-wa-name').value;
            const email = document.getElementById('swal-wa-email').value;
            if (!name || !email) {
                Swal.showValidationMessage('Nama dan Email wajib diisi!');
                return false;
            }
            return { name, email };
        }
    });

    if (formValues) {
        const text = `Halo Admin WanderMed, saya ingin mengajukan reset password karena lupa password & PIN.\n\nNama Akun : ${formValues.name}\nEmail : ${formValues.email}`;
        const waUrl = `https://wa.me/6287775733922?text=${encodeURIComponent(text)}`;
        window.open(waUrl, '_blank');
    }
}

async function triggerDeleteAccountRequest() {
    const { value: email } = await Swal.fire({
        title: 'Ajukan Penghapusan Akun',
        text: 'Masukkan email terdaftar Anda untuk mengirimkan permohonan hapus akun ke admin.',
        input: 'email',
        inputPlaceholder: 'contoh@gmail.com',
        background: '#111827',
        color: '#e8ecf4',
        showCancelButton: true,
        confirmButtonColor: '#e74a3b',
        cancelButtonColor: '#4b5563',
        confirmButtonText: '<i class="fas fa-trash-alt"></i> Kirim Pengajuan',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        inputValidator: (value) => {
            if (!value) {
                return 'Email tidak boleh kosong!';
            }
        },
        preConfirm: (emailValue) => {
            return fetch(window.WanderMedConfig.requestDeleteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.WanderMedConfig.csrfToken
                },
                body: JSON.stringify({ email: emailValue })
            })
            .then(response => {
                if (!response.ok) throw new Error(response.statusText)
                return response.json()
            })
            .catch(error => {
                Swal.showValidationMessage(`Gagal menghubungi server: ${error}`)
            })
        },
        allowOutsideClick: () => !Swal.isLoading()
    });

    if (email) {
        if (email.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: email.message,
                icon: 'success',
                background: '#111827',
                color: '#e8ecf4',
                confirmButtonColor: '#1cc88a'
            });
        } else {
            Swal.fire({
                title: 'Gagal',
                text: email.message || 'Gagal mengirim pengajuan.',
                icon: 'error',
                background: '#111827',
                color: '#e8ecf4',
                confirmButtonColor: '#ff7a00'
            });
        }
    }
}
