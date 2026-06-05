
<nav class="wm-navbar navbar navbar-expand-lg" id="mainNavbar">
    <div class="wm-navbar-inner">


        {{-- ── BRAND (Far Left) ── --}}
        <a class="wm-brand scroll-link" href="/#page-top">
            <div class="wm-brand-icon">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div class="wm-brand-text">
                <span class="wm-brand-name">WanderMed</span>
                <span class="wm-brand-sub">Subang Health Map</span>
            </div>
        </a>

        {{-- ── HAMBURGER (Mobile Only) ── --}}
        <button class="wm-toggler" type="button" id="navbarTogglerBtn"
                data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="wm-toggler-bar"></span>
            <span class="wm-toggler-bar"></span>
            <span class="wm-toggler-bar"></span>
        </button>

        {{-- ── COLLAPSIBLE CONTENT ── --}}
        <div class="collapse navbar-collapse" id="navbarNav">

            {{-- Navigation Links (Center-Left) --}}
            <nav class="wm-nav-links">
                <a class="wm-nav-link scroll-link" href="/#page-top">Beranda</a>
                <a class="wm-nav-link scroll-link" href="/#tentang">Tentang</a>
                <a class="wm-nav-link scroll-link" href="/#panduan">Panduan</a>
                <a class="wm-nav-link scroll-link" href="/#mitra">Mitra</a>
                <a class="wm-nav-link" href="/faq">FAQ</a>
            </nav>

            {{-- Vertical Divider --}}
            <div class="wm-divider d-none d-lg-block"></div>

            {{-- Action Buttons (Far Right) --}}
            <div class="wm-actions">

                {{-- Kontak CS --}}
                <a href="https://wa.me/6287775733922" target="_blank" class="wm-btn wm-btn-cs" title="Chat via WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                    <span>Kontak CS</span>
                </a>

                {{-- Lapor Masalah --}}
                <a href="#" class="wm-btn wm-btn-lapor" data-toggle="modal" data-target="#reportModal" title="Laporkan Masalah">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Lapor</span>
                </a>

                {{-- Login / Dashboard --}}
                @if(session('auth_user'))
                    <a href="{{ url('/login') }}" class="wm-btn wm-btn-login">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="/daftar/wisatawan" class="wm-btn wm-btn-register">
                        <i class="fas fa-user-plus"></i>
                        <span>Daftar</span>
                    </a>
                    <a href="/login" class="wm-btn wm-btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk</span>
                    </a>
                @endif

                {{-- Theme Toggle --}}
                <button type="button" id="themeToggle" class="wm-theme-btn theme-toggle-btn" title="Ubah Tema" aria-label="Toggle Theme">
                    <i class="fas fa-sun" id="themeIcon"></i>
                </button>

            </div>

        </div>
    </div>
</nav>

{{-- ── MODALS ── --}}

<div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
        <div class="modal-content radius-hnb border-0 shadow-lg">
            <div class="modal-header bg-hnb-navy text-white radius-hnb" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-info-circle text-hnb-orange mr-2"></i> Cara Penggunaan</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light text-hnb-navy">
                <ol class="pl-3 mb-0 font-weight-bold" style="font-size: 14px; line-height: 1.8;">
                    <li class="mb-2">Cari fasilitas kesehatan lewat kolom pencarian.</li>
                    <li class="mb-2">Gunakan filter BPJS jika diperlukan.</li>
                    <li class="mb-2">Klik tombol "Lapor" jika menemukan data tidak akurat.</li>
                </ol>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-hnb-orange radius-hnb w-100 font-weight-bold" data-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 450px;">
        <div class="modal-content radius-hnb border-0 shadow-lg">
            <div class="modal-header bg-danger text-white radius-hnb" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0;">
                <h6 class="modal-title font-weight-bold mb-0"><i class="fas fa-exclamation-circle mr-2"></i> Laporkan Masalah</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formLaporMasalah" action="{{ route('lapor.masalah') }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light text-left">
                    <div class="form-group mb-3">
                        <label class="text-hnb-navy font-weight-bold small">Kategori Masalah</label>
                        <select name="subjek" class="form-control radius-hnb shadow-sm border-0" required>
                            <option value="" disabled selected>Pilih Kategori...</option>
                            <option value="Data Salah">Informasi Faskes Salah</option>
                            <option value="Lokasi">Titik Lokasi Tidak Akurat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-hnb-navy font-weight-bold small">Deskripsi Masalah</label>
                        <textarea name="deskripsi" class="form-control radius-hnb shadow-sm border-0" rows="4" placeholder="Ceritakan detail masalah..." required maxlength="200"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light pb-4">
                    <button type="submit" id="btnSubmitLaporan" class="btn btn-danger radius-hnb w-100 font-weight-bold shadow">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/navbar.js') }}"></script>

