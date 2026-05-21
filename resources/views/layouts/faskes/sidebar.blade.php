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
<div class="wm-nav-label">Profil</div>
<a href="#" class="wm-nav-link" id="navProfilFaskes">
    <i class="fas fa-hospital"></i> Profil Faskes
</a>
<a href="#" class="wm-nav-link" id="navKoordinat">
    <i class="fas fa-map-pin"></i> Update Koordinat
</a>
<div class="wm-nav-label">Komunikasi</div>
<a href="#" class="wm-nav-link" id="navChat" style="position:relative;">
    <i class="fas fa-comments"></i> Chat Admin
    <span id="chatNavBadge" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);background:#ff7a00;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;align-items:center;justify-content:center;">0</span>
</a>
<div class="wm-nav-label">Navigasi</div>
<a href="/peta-faskes" class="wm-nav-link">
    <i class="fas fa-map-marked-alt"></i> Lihat di Peta
</a>

<div class="wm-nav-label" style="margin-top:20px;">Keamanan</div>
<div style="padding: 12px 20px; display:flex; flex-direction: column; background: rgba(0,0,0,0.1); border-left: 3px solid #ff7a00; margin-bottom: 8px;">
    <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 6px;"><i class="fas fa-key" style="color:#ff7a00;"></i> PIN Pemulihan Akses</div>
    <div title="Arahkan kursor untuk melihat PIN" style="font-family: monospace; font-size: 18px; font-weight: bold; letter-spacing: 6px; color: var(--text-primary); filter: blur(6px); transition: filter 0.3s; user-select: none; cursor: crosshair;" onmouseover="this.style.filter='blur(0)'; this.style.userSelect='auto';" onmouseout="this.style.filter='blur(6px)'; this.style.userSelect='none';">
        {{ $mitra?->recovery_pin ?? '000000' }}
    </div>
</div>

<a href="/logout" class="wm-nav-link">
    <i class="fas fa-sign-out-alt"></i> Keluar
</a>
