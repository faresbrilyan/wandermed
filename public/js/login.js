
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
