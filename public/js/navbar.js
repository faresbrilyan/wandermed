/* File: public/js/navbar.js */

(function() {
    var toggler = document.getElementById('navbarTogglerBtn');
    var navEl   = document.getElementById('navbarNav');
    var navLinks = document.querySelectorAll('.wm-nav-link');

    if (!toggler || !navEl) return;

    toggler.addEventListener('click', function() {
        var isExpanded = navEl.classList.contains('show');
        toggler.classList.toggle('wm-toggler--open', !isExpanded);
    });

    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992 && navEl.classList.contains('show')) {
                toggler.click();
            }
        });
    });
})();

document.addEventListener('DOMContentLoaded', function() {
    const formLapor = document.getElementById('formLaporMasalah');
    if (formLapor) {
        formLapor.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitLaporan');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            btn.disabled = true;

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    $('#reportModal').modal('hide');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'success', title: 'Terkirim!', text: data.message, confirmButtonColor: '#38a169' });
                    } else { alert(data.message); }
                    formLapor.reset();
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.message || 'Gagal mengirim laporan.', confirmButtonColor: '#e53e3e' });
                    } else { alert(data.message || 'Gagal mengirim laporan.'); }
                }
            })
            .catch(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Gagal terhubung ke server. Silakan periksa koneksi internet Anda.', confirmButtonColor: '#e53e3e' });
                } else { alert('Gagal terhubung ke server.'); }
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});
