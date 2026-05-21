@php $hasPesan = !empty($faskes?->pesan_admin); @endphp
<div class="wm-notif-bell" id="wmBellWrap">
    <div class="wm-topbar-icon" onclick="toggleNotifPanel()" title="Notifikasi" style="cursor:pointer;">
        <i class="fas fa-bell"></i>
        @if($hasPesan)
        <span class="wm-notif-badge">1</span>
        @endif
    </div>
    <div class="wm-notif-panel" id="wmNotifPanel">
        {{-- Header --}}
        <div class="wm-notif-header">
            <span><i class="fas fa-bell"></i> Notifikasi</span>
            @if($hasPesan)
            <span class="wm-notif-header-count">1 Baru</span>
            @endif
        </div>
        {{-- Content --}}
        @if($hasPesan)
        <div class="wm-notif-item unread">
            <div class="wm-notif-icon orange"><i class="fas fa-envelope-open-text"></i></div>
            <div class="wm-notif-content">
                <div class="wm-notif-title">Pesan dari Admin WanderMed</div>
                <div class="wm-notif-body">{{ $faskes?->pesan_admin }}</div>
                <div class="wm-notif-meta"><i class="fas fa-shield-alt"></i> Dikirim oleh Administrator</div>
            </div>
        </div>
        @else
        <div class="wm-notif-empty">
            <i class="fas fa-bell-slash"></i>
            <p>Tidak ada notifikasi baru</p>
        </div>
        @endif
    </div>
</div>
