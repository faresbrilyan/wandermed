{{-- ============================================================
     Chat Room: Sisi Mitra Faskes
     WhatsApp-style, Light/Dark aware via CSS variables
     ============================================================ --}}
<div id="sectionChat" class="faskes-section" style="display:none;">

@push('styles')
<link href="{{ asset('css/chat-mitra.css') }}" rel="stylesheet">
@endpush

<div class="wm-page-header">
    <div>
        <div class="wm-page-title">
            <i class="fas fa-comments" style="color:var(--orange);margin-right:10px;"></i>Chat dengan Admin WanderMed
        </div>
        <div class="wm-page-subtitle">Ruang komunikasi langsung, aman, dan real-time bersama tim Admin</div>
    </div>
</div>

<div class="mc-shell">

    {{-- ── Topbar ── --}}
    <div class="mc-topbar" style="display: flex; justify-content: space-between; align-items: center; padding-right: 20px;">
        <div style="display: flex; align-items: center; gap: 13px; flex:1;">
            <div class="mc-topbar-avatar">A</div>
            <div>
                <div class="mc-topbar-name">Admin WanderMed</div>
                <div class="mc-topbar-sub">Siap membalas pesan Anda</div>
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="mc-unread-badge" id="mcUnreadBadge">0 pesan baru</div>
            <button class="btn btn-sm btn-outline-danger" onclick="mitraClearChat()" title="Hapus Semua Obrolan" style="border-radius: 6px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-trash-alt"></i> Bersihkan
            </button>
        </div>
    </div>

    {{-- ── Messages ── --}}
    <div class="mc-body" id="mcBody">
        <div class="mc-empty" id="mcEmpty">
            <div class="mc-empty-icon"><i class="fas fa-comment-dots"></i></div>
            <div>
                <strong>Belum ada percakapan</strong>
                <p>Kirim pesan pertama Anda ke Admin WanderMed untuk memulai komunikasi.</p>
            </div>
        </div>
    </div>

    {{-- ── Input ── --}}
    <div class="mc-input-area">
        <div class="mc-input-wrap">
            <textarea class="mc-input-box" id="mcInputBox" rows="1"
                placeholder="Ketik pesan untuk Admin WanderMed..."
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();mitraSendMessage();}"
                oninput="autoResizeMc(this)" maxlength="500"></textarea>
        </div>
        <button class="mc-send-btn" id="mcSendBtn" onclick="mitraSendMessage()" title="Kirim Pesan">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>

</div>

</div>{{-- /sectionChat --}}
