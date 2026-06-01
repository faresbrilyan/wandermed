{{-- ============================================================
     Chat Room: Admin ↔ Mitra Faskes
     WhatsApp-style, Light/Dark aware via CSS variables
     ============================================================ --}}

<div id="sectionChat" class="admin-section" style="display:none;">

@push('styles')
<link href="{{ asset('css/chat-admin.css') }}" rel="stylesheet">
@endpush

<div class="wm-page-header">
    <div>
        <div class="wm-page-title">
            <i class="fas fa-comments" style="color:var(--orange);margin-right:10px;"></i>Chat dengan Mitra Faskes
        </div>
        <div class="wm-page-subtitle">Komunikasi dua arah langsung dengan mitra faskes yang terverifikasi</div>
    </div>
</div>

<div class="chat-shell" id="chatShell">

    {{-- ─── SIDEBAR KONTAK ─────────────────────────── --}}
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="chat-sidebar-title">
                <i class="fas fa-hospital-user"></i> Daftar Faskes
            </div>
            <div class="chat-search">
                <i class="fas fa-search"></i>
                <input type="text" id="chatContactSearch" placeholder="Cari faskes..." oninput="filterContacts(this.value)" maxlength="100">
            </div>
        </div>
        <div class="chat-contacts" id="chatContactsList">
            <div class="contact-empty">
                <i class="fas fa-spinner fa-spin"></i>
                Memuat daftar faskes...
            </div>
        </div>
    </div>

    {{-- ─── MAIN CHAT AREA ─────────────────────────── --}}
    <div class="chat-main" id="chatMain">

        {{-- Placeholder --}}
        <div class="chat-placeholder" id="chatPlaceholder">
            <div class="chat-placeholder-icon"><i class="fas fa-comments"></i></div>
            <div>
                <h5>Pilih Faskes untuk Mulai Chat</h5>
                <p style="margin-top:6px;">Pilih salah satu mitra faskes dari daftar di sebelah kiri untuk membuka percakapan.</p>
            </div>
        </div>

        {{-- Area percakapan --}}
        <div id="chatConversation" style="display:none; flex-direction:column; flex:1; overflow:hidden; height:100%;">
            <div class="chat-topbar" style="display: flex; justify-content: space-between; align-items: center; padding-right: 20px;">
                <div style="display: flex; align-items: center; gap: 13px;">
                    <div class="chat-topbar-avatar" id="chatTopAvatar">F</div>
                    <div>
                        <div class="chat-topbar-name" id="chatTopName">Nama Faskes</div>
                        <div class="chat-topbar-sub">Online</div>
                    </div>
                </div>
                <button class="btn btn-sm btn-outline-danger" onclick="adminClearChat()" title="Hapus Semua Obrolan" style="border-radius: 6px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-trash-alt"></i> Bersihkan
                </button>
            </div>

            <div class="chat-body" id="chatBody">
                <div class="chat-empty">
                    <i class="fas fa-comment-medical"></i>
                    <p>Belum ada pesan. Mulai percakapan!</p>
                </div>
            </div>

            <div class="chat-input-area">
                <div class="chat-input-wrap">
                    <textarea class="chat-input-box" id="chatInputAdmin" rows="1"
                        placeholder="Ketik pesan untuk mitra faskes ini..."
                        onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();adminSendMessage();}"
                        oninput="autoResize(this)" maxlength="500"></textarea>
                </div>
                <button class="chat-send-btn" id="chatSendBtnAdmin" onclick="adminSendMessage()" title="Kirim Pesan">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>
</div>

</div>{{-- /sectionChat --}}
