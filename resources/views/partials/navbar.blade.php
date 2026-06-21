
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
                <a href="#" class="wm-btn wm-btn-cs" data-toggle="modal" data-target="#csTemplateModal" title="Pilih Template Chat CS">
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
                        <label class="text-hnb-navy font-weight-bold small">Faskes Terkait (Opsional)</label>
                        <select name="faskes_id" class="form-control radius-hnb shadow-sm border-0">
                            <option value="">– Tidak Ada / Umum –</option>
                            @foreach(\App\Models\Faskes::orderBy('nama_faskes')->get(['id', 'nama_faskes']) as $fs)
                                <option value="{{ $fs->id }}">{{ $fs->nama_faskes }}</option>
                            @endforeach
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

<div class="modal fade" id="csTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 420px;">
        <div class="modal-content radius-hnb border-0 shadow-lg cs-modal-content">
            <div class="modal-header bg-success text-white radius-hnb" style="border-bottom-left-radius: 0; border-bottom-right-radius: 0; background: linear-gradient(135deg, #2ecc71, #27ae60) !important;">
                <h6 class="modal-title font-weight-bold mb-0"><i class="fab fa-whatsapp mr-2" style="font-size: 1.1rem;"></i> Chat CS WanderMed</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 bg-light text-left">
                <p class="text-muted small mb-3 text-center" style="font-family: 'Poppins', sans-serif;">Pilih template pesan untuk memulai obrolan dengan Customer Service kami via WhatsApp:</p>
                <div class="cs-templates-list">
                    {{-- Template 1: Masalah Akun / Teknis --}}
                    <a href="https://wa.me/6287775733922?text=Halo%20Admin%20WanderMed%2C%20saya%20mengalami%20masalah%20teknis%2Fkendala%20pada%20akun%20saya.%20Mohon%20bantuannya." 
                       target="_blank" 
                       class="cs-template-card"
                       onclick="$('#csTemplateModal').modal('hide');">
                        <div class="cs-card-icon bg-warning-light">
                            <i class="fas fa-tools text-warning"></i>
                        </div>
                        <div class="cs-card-info">
                            <div class="cs-card-title">Masalah Teknis & Akun</div>
                            <div class="cs-card-desc">Kendala login, error aplikasi, atau edit data.</div>
                        </div>
                        <i class="fas fa-chevron-right cs-card-arrow"></i>
                    </a>

                    {{-- Template 2: Kerja Sama / Kemitraan --}}
                    <a href="https://wa.me/6287775733922?text=Halo%20Admin%20WanderMed%2C%20saya%20tertarik%20untuk%20mengajukan%20kerja%20sama%20kemitraan.%20Bagaimana%20langkah%20selanjutnya%3F" 
                       target="_blank" 
                       class="cs-template-card"
                       onclick="$('#csTemplateModal').modal('hide');">
                        <div class="cs-card-icon bg-info-light">
                            <i class="fas fa-handshake text-info"></i>
                        </div>
                        <div class="cs-card-info">
                            <div class="cs-card-title">Pengajuan Kerja Sama</div>
                            <div class="cs-card-desc">Pendaftaran mitra faskes atau pariwisata baru.</div>
                        </div>
                        <i class="fas fa-chevron-right cs-card-arrow"></i>
                    </a>

                    {{-- Template 3: Keluhan & Saran --}}
                    <a href="https://wa.me/6287775733922?text=Halo%20Admin%20WanderMed%2C%20saya%20mengalami%20keluhan%20atau%20ingin%20memberikan%20saran%20mengenai%20layanan.%20Berikut%20detailnya%3A" 
                       target="_blank" 
                       class="cs-template-card"
                       onclick="$('#csTemplateModal').modal('hide');">
                        <div class="cs-card-icon bg-danger-light">
                            <i class="fas fa-comment-dots text-danger"></i>
                        </div>
                        <div class="cs-card-info">
                            <div class="cs-card-title">Keluhan & Saran</div>
                            <div class="cs-card-desc">Kirim masukan atau laporan penyalahgunaan.</div>
                        </div>
                        <i class="fas fa-chevron-right cs-card-arrow"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/navbar.js') }}"></script>

