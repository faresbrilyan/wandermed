<div class="wm-nav-label">Overview</div>
<a href="#" class="wm-nav-link active" id="navDashboard">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>

<div class="wm-nav-label">Validasi & Moderasi</div>
<a href="#" class="wm-nav-link" id="navValidasi">
    <i class="fas fa-user-check"></i> Validasi Mitra
    <span class="badge-pill-side" id="navPendingCount">{{ $pendingMitra ?? 0 }}</span>
</a>
<a href="#" class="wm-nav-link" id="navLaporan">
    <i class="fas fa-exclamation-triangle"></i> Laporan Masalah
    <span class="badge-pill-side">{{ isset($laporans) ? $laporans->where('status', 'pending')->count() : 0 }}</span>
</a>

<div class="wm-nav-label">Data Master</div>
<a href="#" class="wm-nav-link" id="navDataFaskes">
    <i class="fas fa-clinic-medical"></i> Fasilitas Kesehatan
</a>
<a href="#" class="wm-nav-link" id="navDataPariwisata">
    <i class="fas fa-mountain"></i> Destinasi Pariwisata
</a>
<a href="#" class="wm-nav-link" id="navDataWisatawan">
    <i class="fas fa-users"></i> Data Wisatawan
</a>
<a href="#" class="wm-nav-link" id="navAllUlasan">
    <i class="fas fa-star"></i> Ulasan Faskes
</a>

<div class="wm-nav-label">Komunikasi</div>
<a href="#" class="wm-nav-link" id="navChat" style="position:relative;">
    <i class="fas fa-comments"></i> Chat Faskes
    <span id="adminChatBadge" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);background:#ff7a00;color:#fff;border-radius:50%;width:18px;height:18px;font-size:10px;font-weight:700;display:none;align-items:center;justify-content:center;">0</span>
</a>

<div class="wm-nav-label">Sistem</div>
<a href="/peta-faskes" class="wm-nav-link">
    <i class="fas fa-map-marked-alt"></i> Lihat Peta Publik
</a>
<a href="/logout" class="wm-nav-link">
    <i class="fas fa-sign-out-alt"></i> Keluar
</a>
