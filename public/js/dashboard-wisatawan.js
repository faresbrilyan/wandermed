/* ================================================================
 * dashboard-wisatawan.js – WanderMed
 * ================================================================ */

// ── Sinkronkan ikon tema setelah DOM siap ──
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.body.classList.contains('dark');
    const ico = document.getElementById('themeIco');
    if (ico) ico.className = isDark ? 'fas fa-moon' : 'fas fa-sun';
});

// ── TOGGLE TEMA ──
function toggleTheme() {
    const isDark = document.body.classList.contains('dark');

    if (isDark) {
        // Beralih ke light mode
        document.body.classList.remove('dark');
        localStorage.setItem('wanderMedTheme', 'light');
        const ico = document.getElementById('themeIco');
        if (ico) ico.className = 'fas fa-sun';
    } else {
        // Beralih ke dark mode
        document.body.classList.add('dark');
        localStorage.setItem('wanderMedTheme', 'dark');
        const ico = document.getElementById('themeIco');
        if (ico) ico.className = 'fas fa-moon';
    }
}



// ── SWITCH TAB ──
function switchTab(name) {
    // Pane
    document.querySelectorAll('.w-pane').forEach(p => p.classList.remove('active'));
    const pane = document.getElementById('tab-' + name);
    if (pane) pane.classList.add('active');

    // Pills (mobile bar)
    document.querySelectorAll('.w-pill').forEach(b => b.classList.remove('active'));
    const pill = document.getElementById('pill-' + name);
    if (pill) pill.classList.add('active');

    // Sidebar nav (desktop)
    document.querySelectorAll('.sidebar-nav-item').forEach(b => b.classList.remove('active'));
    const sn = document.getElementById('sn-' + name);
    if (sn) sn.classList.add('active');
}

// ── SCROLL KE MAIN CONTENT (untuk tombol di profile-strip mobile) ──
function scrollToMain() {
    const main = document.getElementById('wMain');
    if (main) main.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── LOGOUT KONFIRMASI ──
document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');
    if (!logoutBtn) return;

    logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const dest = this.href;

        Swal.fire({
            title: 'Keluar dari Akun?',
            html: `<p style="color:var(--text-muted);font-size:14px;margin:0;line-height:1.6;">
                       Sesi Anda akan diakhiri dan Anda akan<br>diarahkan kembali ke halaman login.
                   </p>`,
            icon: 'warning',
            iconColor: '#ff7a00',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Ya, Keluar',
            cancelButtonText:  '<i class="fas fa-times" style="margin-right:6px;"></i>Batal',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup:         'wm-swal-popup',
                title:         'wm-swal-title',
                confirmButton: 'wm-swal-confirm',
                cancelButton:  'wm-swal-cancel',
            },
        }).then(function (result) {
            if (result.isConfirmed) {
                document.body.style.transition = 'opacity 0.18s ease';
                document.body.style.opacity    = '0';
                setTimeout(() => { window.location.href = dest; }, 180);
            }
        });
    });
});

function requestAccountDeletionFromDashboard(emailAddress, csrfToken) {
    Swal.fire({
        title: 'Ajukan Penghapusan Akun',
        html: `<p style="color:var(--text-muted);font-size:14px;margin:0;line-height:1.6;">
                   Apakah Anda yakin ingin mengajukan penghapusan akun wisatawan Anda ke admin? Akun beserta seluruh data rekam medis Anda akan dihapus secara permanen.
               </p>`,
        icon: 'warning',
        iconColor: '#ff4d4d',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-trash-alt" style="margin-right:6px;"></i>Ya, Kirim Pengajuan',
        cancelButtonText:  '<i class="fas fa-times" style="margin-right:6px;"></i>Batal',
        reverseButtons: true,
        focusCancel: true,
        showLoaderOnConfirm: true,
        customClass: {
            popup:         'wm-swal-popup',
            title:         'wm-swal-title',
            confirmButton: 'wm-swal-confirm',
            cancelButton:  'wm-swal-cancel',
        },
        preConfirm: () => {
            return fetch('/wisatawan/request-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ email: emailAddress })
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
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    html: `<p style="color:var(--text-muted);font-size:14px;margin:0;line-height:1.6;">${result.value.message}</p>`,
                    icon: 'success',
                    iconColor: '#28a745',
                    customClass: {
                        popup:         'wm-swal-popup',
                        title:         'wm-swal-title',
                        confirmButton: 'wm-swal-confirm',
                    }
                });
            } else {
                Swal.fire({
                    title: 'Gagal',
                    html: `<p style="color:var(--text-muted);font-size:14px;margin:0;line-height:1.6;">${result.value.message || 'Gagal mengirim pengajuan.'}</p>`,
                    icon: 'error',
                    iconColor: '#ff4d4d',
                    customClass: {
                        popup:         'wm-swal-popup',
                        title:         'wm-swal-title',
                        confirmButton: 'wm-swal-confirm',
                    }
                });
            }
        }
    });
}