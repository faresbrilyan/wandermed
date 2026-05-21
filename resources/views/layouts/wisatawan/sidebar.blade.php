<aside class="w-sidebar" id="wSidebar">

    {{-- Profil --}}
    <div class="profile-main">
        <div class="p-avatar">{{ strtoupper(substr($user?->name ?? 'W', 0, 1)) }}</div>
        <div class="p-name">{{ $user?->name ?? 'Wisatawan' }}</div>
        <div class="p-email">{{ $user?->email ?? '—' }}</div>
        <div class="p-badge"><i class="fas fa-check-circle"></i> Wisatawan Aktif</div>
        <div class="p-join"><i class="fas fa-calendar-alt"></i> Bergabung {{ $user?->created_at ? $user->created_at->format('M Y') : '—' }}</div>
    </div>

    <div class="sidebar-divider"></div>

    {{-- Navigasi Tab --}}
    <nav class="sidebar-nav">
        <button class="sidebar-nav-item active" id="sn-riwayat" onclick="switchTab('riwayat')">
            <i class="fas fa-history"></i> Riwayat Kunjungan
        </button>
        <button class="sidebar-nav-item" id="sn-profil" onclick="switchTab('profil')">
            <i class="fas fa-user-cog"></i> Pengaturan Akun
        </button>
        <button class="sidebar-nav-item" id="sn-medis" onclick="switchTab('medis')">
            <i class="fas fa-notes-medical"></i> Rekam Medis
        </button>
    </nav>

    <div class="sidebar-divider"></div>

    {{-- Info Medis Darurat --}}
    <div class="medis-summary">
        <div class="medis-summary-title">Info Medis Darurat</div>
        <div class="med-row">
            <div class="med-lbl"><i class="fas fa-tint" style="color:var(--red)"></i> Gol. Darah</div>
            <div class="med-val red">{{ $user?->gol_darah ?: '—' }}</div>
        </div>
        <div class="med-row">
            <div class="med-lbl"><i class="fas fa-phone-alt" style="color:var(--orange)"></i> Kontak Darurat</div>
            <div class="med-val" style="font-size:11px; text-align:right; max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                {{ $user?->kontak_darurat ?: '—' }}
            </div>
        </div>
    </div>

    <div class="sidebar-divider"></div>

    {{-- PIN Pemulihan --}}
    <div class="medis-summary">
        <div class="pin-toggle-row">
            <span><i class="fas fa-key"></i> PIN Pemulihan</span>
            <label class="switch">
                <input type="checkbox" id="togglePinWisatawan" onchange="togglePinVisibility()">
                <span class="slider"></span>
            </label>
        </div>
        <div class="pin-display">
            <span id="pinValueWisatawan" style="filter:blur(5px); transition:filter 0.3s; user-select:none;">
                {{ $user?->recovery_pin ?? '000000' }}
            </span>
        </div>
        <div class="pin-hint">Gunakan 6-digit PIN ini jika lupa password akun Anda.</div>
    </div>

</aside>
