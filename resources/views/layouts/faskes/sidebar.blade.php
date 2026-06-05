<div class="wm-nav-label">Operasional</div>
<a href="#" class="wm-nav-link active" id="navDashboard">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="#" class="wm-nav-link" id="navKontrolStatus">
    <i class="fas fa-toggle-on"></i> Kontrol Status
</a>
<a href="#" class="wm-nav-link" id="navJadwal">
    <i class="fas fa-calendar-alt"></i> Jadwal Praktik
</a>
<a href="#" class="wm-nav-link" id="navFasilitas">
    <i class="fas fa-clipboard-list"></i> Fasilitas & Layanan
</a>
<div class="wm-nav-label">Feedback</div>
<a href="#" class="wm-nav-link" id="navUlasan">
    <i class="fas fa-star"></i> Ulasan Wisatawan
</a>
<div class="wm-nav-label">Profil & Lokasi</div>
<a href="#" class="wm-nav-link" id="navProfilFaskes">
    <i class="fas fa-hospital"></i> Profil & Lokasi Faskes
</a>
<div class="wm-nav-label">Komunikasi</div>
<a href="#" class="wm-nav-link" id="navChat" style="position:relative;">
    <i class="fas fa-comments"></i> Chat Admin
    <span id="chatNavBadge" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);background:#ff7a00;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;align-items:center;justify-content:center;">0</span>
</a>
<div class="wm-nav-label">Navigasi</div>
<a href="#" class="wm-nav-link" id="navRiwayatLogin">
    <i class="fas fa-history"></i> Riwayat Login
</a>
<a href="/peta-faskes" class="wm-nav-link">
    <i class="fas fa-map-marked-alt"></i> Lihat di Peta
</a>



<div style="padding: 12px 20px 5px 20px; font-size: 11px; color: var(--text-muted); border-top: 1px solid var(--border); margin-top: 15px; line-height: 1.6;">
    <i class="fas fa-history" style="color: #ff7a00; margin-right: 4px;"></i> Terakhir Login: <br>
    <span style="font-weight: 600; color: var(--text-primary); margin-top: 4px; display: inline-block;">{{ session('auth_user.last_login_at') }}</span>
</div>

<a href="/logout" class="wm-nav-link">
    <i class="fas fa-sign-out-alt"></i> Keluar
</a>
