@php
    $bellMitraCount   = ($mitraPending ?? collect())->count() + ($wisataPending ?? collect())->count();
    $bellTicketCount  = isset($laporans) ? $laporans->where('status', 'pending')->count() : 0;
    $bellTotal        = $bellMitraCount + $bellTicketCount;
    $recentMitra      = ($mitraPending ?? collect())->take(2);
    $recentWisata     = ($wisataPending ?? collect())->take(2);
    $recentTickets    = isset($laporans) ? $laporans->where('status', 'pending')->take(3) : collect();
@endphp
<div class="wm-notif-bell" id="wmBellWrap">
    <div class="wm-topbar-icon" onclick="toggleNotifPanel()" title="Notifikasi" style="cursor:pointer;">
        <i class="fas fa-bell"></i>
        @if($bellTotal > 0)
        <span class="wm-notif-badge">{{ $bellTotal > 9 ? '9+' : $bellTotal }}</span>
        @endif
    </div>
    <div class="wm-notif-panel" id="wmNotifPanel">
        {{-- Header --}}
        <div class="wm-notif-header">
            <span><i class="fas fa-bell"></i> Notifikasi</span>
            @if($bellTotal > 0)
            <span class="wm-notif-header-count">{{ $bellTotal }} Baru</span>
            @endif
        </div>

        @if($bellTotal === 0)
        <div class="wm-notif-empty">
            <i class="fas fa-bell-slash"></i>
            <p>Tidak ada notifikasi baru</p>
        </div>
        @else

        {{-- SECTION: VALIDASI MITRA --}}
        @if($bellMitraCount > 0)
        <div class="wm-notif-section-label">
            <i class="fas fa-user-check" style="margin-right:5px;color:var(--orange);"></i>
            Validasi Mitra ({{ $bellMitraCount }})
        </div>
        @foreach($recentMitra as $mp)
        <div class="wm-notif-item unread" onclick="wmSearchNavigate(document.querySelector('[data-nav-id=navValidasi]') ? {getAttribute:()=>'navValidasi'} : this)">
            <div class="wm-notif-icon orange"><i class="fas fa-clinic-medical"></i></div>
            <div class="wm-notif-content">
                <div class="wm-notif-title">{{ $mp->nama_penanggung_jawab }}</div>
                <div class="wm-notif-body">{{ $mp->faskes ? $mp->faskes->nama_faskes : 'Mitra Faskes' }} · Menunggu persetujuan</div>
                <div class="wm-notif-meta"><i class="fas fa-clock"></i> {{ $mp->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
        @foreach($recentWisata as $wp)
        <div class="wm-notif-item unread">
            <div class="wm-notif-icon teal"><i class="fas fa-mountain"></i></div>
            <div class="wm-notif-content">
                <div class="wm-notif-title">{{ $wp->nama_pengelola }}</div>
                <div class="wm-notif-body">{{ $wp->nama_wisata }} · Menunggu persetujuan</div>
                <div class="wm-notif-meta"><i class="fas fa-clock"></i> {{ $wp->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
        @endif

        {{-- SECTION: LAPORAN MASALAH --}}
        @if($bellTicketCount > 0)
        <div class="wm-notif-section-label">
            <i class="fas fa-exclamation-triangle" style="margin-right:5px;color:#f6c23e;"></i>
            Laporan Masalah ({{ $bellTicketCount }})
        </div>
        @foreach($recentTickets as $tk)
        <div class="wm-notif-item unread">
            <div class="wm-notif-icon yellow"><i class="fas fa-ticket-alt"></i></div>
            <div class="wm-notif-content">
                <div class="wm-notif-title">#TKT-{{ str_pad($tk->id, 4, '0', STR_PAD_LEFT) }} · {{ $tk->user->name ?? 'Wisatawan' }}</div>
                <div class="wm-notif-body">{{ Str::limit($tk->deskripsi, 70) }}</div>
                <div class="wm-notif-meta"><i class="fas fa-clock"></i> {{ $tk->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
        @endif

        {{-- Footer --}}
        <div class="wm-notif-footer">
            @if($bellMitraCount > 0)
            <a href="#" onclick="wmSearchNavigate({getAttribute:()=>'navValidasi'});return false;">
                <i class="fas fa-user-check"></i> Validasi
            </a>
            @endif
            @if($bellTicketCount > 0)
            <a href="#" onclick="wmSearchNavigate({getAttribute:()=>'navLaporan'});return false;">
                <i class="fas fa-exclamation-triangle"></i> Laporan
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
