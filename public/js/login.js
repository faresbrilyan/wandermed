async function forgotPasswordTelegram(e) {
    e.preventDefault();
    const botUser = window.WanderMedConfig.telegramBotUsername || 'wandermed_recovery_bot';
    Swal.fire({
        title: 'Reset Password via Telegram',
        html: `
            <div style="text-align: left; line-height: 1.6; font-size: 14px; color: #cbd5e1;">
                <p>Fitur ini mendukung akun <b>Wisatawan</b> dan <b>Mitra Faskes</b>. Untuk menyetel ulang kata sandi Anda secara instan:</p>
                <ol style="padding-left: 20px; margin-bottom: 15px;">
                    <li>Buka bot Telegram kami: <b>@${botUser}</b></li>
                    <li>Kirim pesan perintah: <code style="color: #ff7a00; font-weight: bold; background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 4px;">/reset [email_anda]</code></li>
                    <li>Bot akan mengirimkan kata sandi sementara jika akun Anda telah terhubung.</li>
                </ol>
                <p style="margin-top: 10px; font-size: 13px; text-align: center;">
                    <a href="/panduan-telegram" target="_blank" style="color: #ff7a00; text-decoration: underline; font-weight: 600;">
                        <i class="fas fa-book-open"></i> Lihat Panduan Lengkap Integrasi
                    </a>
                </p>
            </div>
        `,
        icon: 'info',
        background: '#111827',
        color: '#e8ecf4',
        showCancelButton: true,
        confirmButtonColor: '#0088cc',
        cancelButtonColor: '#4b5563',
        confirmButtonText: '<i class="fab fa-telegram-plane"></i> Buka Telegram',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open(`https://t.me/${botUser}`, '_blank');
        }
    });
}

async function requestDeleteAccount(e) {
    e.preventDefault();
    
    const { value: email } = await Swal.fire({
        title: 'Ajukan Penghapusan Akun',
        text: 'Masukkan email terdaftar Anda untuk mengirimkan permohonan penghapusan akun kepada admin.',
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
