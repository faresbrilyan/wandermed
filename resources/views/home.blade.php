@extends('layouts.public')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link href="{{ asset('css/wisatawan-home.css') }}" rel="stylesheet">
@endpush

@section('content')
    @include('partials.navbar')

    <div id="page-top"></div>

    {{-- ── 1. HERO SECTION (Always Overlay on Dark Map) ── --}}
    <section class="hero-slanted section-scroll" style="position:relative;">
        {{-- Map Background Layer --}}
        <div id="hero-bg-map"></div>
        <div class="hero-map-overlay"></div>

        {{-- Live Status Badge --}}
        <div class="hero-map-badge">
            <span class="dot-live"></span> Active GIS Node · Subang
        </div>

        {{-- Hero Contents --}}
        <div class="container px-4" style="position:relative; z-index:2;">
            <div class="row align-items-center">
                <div class="col-lg-7 text-left mb-5 mb-lg-0" data-aos="fade-right" data-aos-duration="700">
                    <div class="hero-content-box" style="padding: 48px;">
                        <div class="mb-4" style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,122,0,0.1); border:1px solid rgba(255,122,0,0.25); border-radius:30px; padding:6px 16px;">
                            <i data-lucide="map-pin" class="saas-icon saas-icon-orange" style="width: 14px; height: 14px;"></i>
                            <span style="font-size:11px; font-weight:700; color:var(--saas-orange); letter-spacing:1px; text-transform:uppercase;">GIS Medical Mapping System</span>
                        </div>
                        
                        <h1 class="font-weight-bold text-white mb-3" style="font-size: 3.2rem; line-height: 1.15; font-family:'Poppins', sans-serif;">
                            Navigasi Medis,<br>
                            <span style="color: var(--saas-orange);">Real-Time & Akurat.</span>
                        </h1>
                        
                        <p class="text-white-50 mb-5" style="font-size: 1.05rem; max-width: 540px; line-height: 1.75;">
                            Integrasi spasial cerdas untuk memetakan UGD, Puskesmas, Klinik, dan Apotek di seluruh rute wisata Subang. Respons cepat saat darurat demi kenyamanan perjalanan Anda.
                        </p>

                        <div class="d-flex flex-wrap align-items-center" style="gap:16px;">
                            <a href="/peta-faskes" class="btn btn-saas-primary">
                                <i data-lucide="map"></i> Buka Peta Interaktif
                            </a>
                            <a href="#tentang" class="btn btn-saas-secondary">
                                Pelajari Sistem <i data-lucide="arrow-down" class="saas-icon" style="width: 16px; height: 16px;"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Hero Side Logo Panel --}}
                <div class="col-lg-5 d-none d-lg-flex justify-content-center" data-aos="zoom-in" data-aos-duration="800" data-aos-delay="200">
                    <div class="d-flex align-items-center justify-content-center hero-logo-box">
                        <img src="{{ asset('img/wdm.png') }}" alt="WanderMed Logo" class="logo-for-light-bg" style="width: 240px; height: 240px; object-fit: contain;">
                        <img src="{{ asset('img/wdmlight.png') }}" alt="WanderMed Logo" class="logo-for-dark-bg" style="width: 240px; height: 240px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="scroll-indicator" id="heroScrollIndicator" style="cursor: pointer;">
            <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--saas-orange);">Scroll</span>
            <i data-lucide="chevron-down" class="saas-icon saas-icon-orange" style="margin-top: 4px;"></i>
        </div>
    </section>

    {{-- ── 2. TENTANG SECTION (SaaS Bento Grid) ── --}}
    <section id="tentang" class="py-5 section-scroll" style="background: var(--saas-bg) !important; transition: background 0.3s ease;">
        <div class="container px-4 mt-5 mb-5">
            {{-- ── Statistics Panel (Moved to top, right below hero) ── --}}
            <div class="saas-stats-grid mb-5" style="max-width: 1050px; margin: 0 auto 56px auto;" data-aos="fade-up" data-aos-duration="600">
                <div class="saas-card p-4 text-center">
                    <h3 class="font-weight-bold mb-1" style="color: var(--saas-text-primary) !important; font-size: 2.2rem; font-family:'Poppins', sans-serif;">15+</h3>
                    <span class="text-muted" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--saas-text-secondary) !important;">RSUD & Puskesmas</span>
                </div>
                <div class="saas-card p-4 text-center">
                    <h3 class="font-weight-bold mb-1" style="color: var(--saas-orange) !important; font-size: 2.2rem; font-family:'Poppins', sans-serif;">24/7</h3>
                    <span class="text-muted" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--saas-text-secondary) !important;">Spatio-Temporal Monitor</span>
                </div>
                <div class="saas-card p-4 text-center">
                    <h3 class="font-weight-bold mb-1" style="color: var(--saas-text-primary) !important; font-size: 2.2rem; font-family:'Poppins', sans-serif;">10K+</h3>
                    <span class="text-muted" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--saas-text-secondary) !important;">Wisatawan Terproteksi</span>
                </div>
                <div class="saas-card p-4 text-center">
                    <h3 class="font-weight-bold mb-1" style="color: var(--saas-text-primary) !important; font-size: 2.2rem; font-family:'Poppins', sans-serif;">120+</h3>
                    <span class="text-muted" style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--saas-text-secondary) !important;">Destinasi Terpetakan</span>
                </div>
            </div>

            <div class="text-center mb-5 pb-4" data-aos="fade-up" data-aos-duration="600">
                <h2 class="font-weight-bold mb-3" style="color: var(--saas-text-primary) !important; font-size: 2.2rem; font-family:'Poppins', sans-serif;">Teknologi & Infrastruktur</h2>
                <div style="width: 48px; height: 3px; background-color: var(--saas-orange); margin: 0 auto 24px auto; border-radius: 2px;"></div>
                <p class="mx-auto text-muted" style="max-width: 600px; font-size: 1rem; line-height: 1.6; color: var(--saas-text-secondary) !important;">Bagaimana WanderMed mengintegrasikan kesiapan faskes dengan spasial destinasi wisata di Kabupaten Subang.</p>
            </div>

            <div class="row g-4 justify-content-center">
                {{-- Card 1: Visi Utama --}}
                <div class="col-lg-5 mb-4 px-3" data-aos="fade-right" data-aos-duration="600">
                    <div class="saas-card p-5 h-100 position-relative overflow-hidden" style="border-left: 4px solid var(--saas-orange) !important;">
                        <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255,122,0,0.06);">
                            <i data-lucide="activity" class="saas-icon saas-icon-orange"></i>
                        </div>
                        <h3 class="font-weight-bold mb-3" style="color: var(--saas-text-primary) !important; font-size: 1.5rem;">Akurasi Spasial GIS</h3>
                        <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.75; color: var(--saas-text-secondary) !important;">
                            Menghubungkan koordinat geografis wisatawan dengan fasilitas kesehatan terdekat menggunakan metode perhitungan jarak geodesik (Haversine Formula) untuk rujukan tercepat saat terjadi insiden darurat medis di destinasi.
                        </p>
                    </div>
                </div>

                {{-- Card 2 & 3 Right Columns --}}
                <div class="col-lg-7">
                    <div class="row h-100 align-content-stretch">
                        <div class="col-sm-6 mb-4 px-3" data-aos="fade-down" data-aos-delay="100" data-aos-duration="600">
                            <div class="saas-card p-4 h-100">
                                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 10px; background: rgba(10, 20, 40, 0.04);">
                                    <i data-lucide="server" class="saas-icon"></i>
                                </div>
                                <h5 class="font-weight-bold mb-2" style="color: var(--saas-text-primary) !important;">Sinkronisasi Mitra</h5>
                                <p class="text-muted mb-0" style="font-size: 0.88rem; line-height: 1.6; color: var(--saas-text-secondary) !important;">Pembaruan status operasional faskes, ketersediaan kasur UGD, dan jadwal dokter dikelola langsung oleh mitra faskes.</p>
                            </div>
                        </div>

                        <div class="col-sm-6 mb-4 px-3" data-aos="fade-down" data-aos-delay="200" data-aos-duration="600">
                            <div class="saas-card p-4 h-100">
                                <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 10px; background: rgba(10, 20, 40, 0.04);">
                                    <i data-lucide="heart-pulse" class="saas-icon"></i>
                                </div>
                                <h5 class="font-weight-bold mb-2" style="color: var(--saas-text-primary) !important;">Kesiapan Tanggap Darurat</h5>
                                <p class="text-muted mb-0" style="font-size: 0.88rem; line-height: 1.6; color: var(--saas-text-secondary) !important;">Identifikasi cepat faskes yang mendukung BPJS Kesehatan atau UGD 24 jam dengan satu klik langsung dari perangkat Anda.</p>
                            </div>
                        </div>

                        <div class="col-12 mb-4 px-3" data-aos="fade-up" data-aos-delay="300" data-aos-duration="600">
                            <div class="saas-card p-4 h-100 d-flex flex-column flex-sm-row align-items-start align-items-sm-center" style="gap: 20px;">
                                <div class="d-inline-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(10, 20, 40, 0.04); flex-shrink: 0;">
                                    <i data-lucide="shield-check" class="saas-icon"></i>
                                </div>
                                <div>
                                    <h5 class="font-weight-bold mb-2" style="color: var(--saas-text-primary) !important;">Verifikasi Akreditasi Faskes</h5>
                                    <p class="text-muted mb-0" style="font-size: 0.9rem; line-height: 1.6; color: var(--saas-text-secondary) !important;">Seluruh fasilitas kesehatan yang terdaftar melewati proses verifikasi ketat dokumen resmi oleh Dinas Kesehatan Kabupaten Subang untuk menjamin validitas layanan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 3. PANDUAN PENGGUNAAN (WanderMed GIS User Flow) ── --}}
    <section id="panduan" class="py-5 section-scroll" style="background: var(--saas-slate) !important; border-top: 1px solid var(--saas-border) !important; border-bottom: 1px solid var(--saas-border) !important; transition: background 0.3s ease;">
        <div class="container px-4 my-5 d-flex flex-column align-items-center">
            <div class="text-center mb-5 pb-2" data-aos="fade-up" data-aos-duration="600">
                <h2 class="font-weight-bold mb-3" style="color: var(--saas-text-primary) !important; font-size: 2.2rem; font-family:'Poppins', sans-serif;">Alur Kerja GIS WanderMed</h2>
                <div style="width: 48px; height: 3px; background-color: var(--saas-orange); margin: 0 auto 24px auto; border-radius: 2px;"></div>
                <p class="text-muted mx-auto" style="max-width: 600px; font-size: 1rem; line-height: 1.6; color: var(--saas-text-secondary) !important;">Ikuti 4 langkah terintegrasi sistem informasi geografis untuk merujuk dan mendapatkan penanganan medis darurat.</p>
            </div>

            <div class="saas-timeline w-100" style="max-width: 1100px;">
                {{-- Step 1 --}}
                <div class="saas-timeline-step" data-aos="fade-up" data-aos-delay="0" data-aos-duration="600">
                    <div class="saas-step-num">1</div>
                    <div class="mt-3">
                        <div class="mb-3 d-flex justify-content-center justify-content-lg-center">
                            <i data-lucide="map-pin" class="saas-icon saas-icon-orange"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2" style="color: var(--saas-text-primary) !important; font-size: 1.05rem;">Identifikasi Destinasi</h5>
                        <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6; color: var(--saas-text-secondary) !important;">Pilih objek wisata Subang yang sedang Anda kunjungi (misal: Sari Ater, Tangkuban Perahu) pada peta interaktif GIS.</p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="saas-timeline-step" data-aos="fade-up" data-aos-delay="150" data-aos-duration="600">
                    <div class="saas-step-num">2</div>
                    <div class="mt-3">
                        <div class="mb-3 d-flex justify-content-center justify-content-lg-center">
                            <i data-lucide="filter" class="saas-icon saas-icon-orange"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2" style="color: var(--saas-text-primary) !important; font-size: 1.05rem;">Saring & Cari Faskes</h5>
                        <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6; color: var(--saas-text-secondary) !important;">Gunakan filter pintar untuk menemukan Rumah Sakit, Puskesmas, atau Apotek terdekat yang beroperasi 24 Jam atau menerima BPJS.</p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="saas-timeline-step" data-aos="fade-up" data-aos-delay="300" data-aos-duration="600">
                    <div class="saas-step-num">3</div>
                    <div class="mt-3">
                        <div class="mb-3 d-flex justify-content-center justify-content-lg-center">
                            <i data-lucide="info" class="saas-icon saas-icon-orange"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2" style="color: var(--saas-text-primary) !important; font-size: 1.05rem;">Verifikasi Detail Layanan</h5>
                        <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6; color: var(--saas-text-secondary) !important;">Periksa fasilitas medis, ketersediaan ambulans, dokter jaga aktif, stok obat-obatan, serta info kontak darurat instan faskes.</p>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="saas-timeline-step" data-aos="fade-up" data-aos-delay="450" data-aos-duration="600">
                    <div class="saas-step-num">4</div>
                    <div class="mt-3">
                        <div class="mb-3 d-flex justify-content-center justify-content-lg-center">
                            <i data-lucide="navigation" class="saas-icon saas-icon-orange"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2" style="color: var(--saas-text-primary) !important; font-size: 1.05rem;">Mulai Navigasi Rute</h5>
                        <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.6; color: var(--saas-text-secondary) !important;">Dapatkan kalkulasi rute spasial darurat tercepat secara real-time langsung dari lokasi wisata Anda menuju gerbang faskes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 4. KEMITRAAN & STATISTIK SECTION ── --}}
    <section id="mitra" class="py-5 section-scroll" style="background: var(--saas-bg) !important; transition: background 0.3s ease;">
        <div class="container px-4 my-5">
            <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="600">
                <h2 class="font-weight-bold mb-3" style="color: var(--saas-text-primary) !important; font-size: 2.2rem; font-family:'Poppins', sans-serif;">Kemitraan & Ekosistem</h2>
                <div style="width: 48px; height: 3px; background-color: var(--saas-orange); margin: 0 auto 24px auto; border-radius: 2px;"></div>
                <p class="text-muted mx-auto" style="max-width: 600px; font-size: 1rem; line-height: 1.6; color: var(--saas-text-secondary) !important;">Berkolaborasi bersama kami membangun standar baru keselamatan medis pariwisata terintegrasi.</p>
            </div>

            <div class="row align-items-stretch justify-content-center" style="gap: 24px 0; max-width: 1050px; margin: 0 auto;">
                {{-- Column Left: Partnership Benefits --}}
                <div class="col-md-6 mb-4 px-3" data-aos="fade-right" data-aos-duration="600">
                    <div class="saas-card p-5 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <h4 class="font-weight-bold mb-4 pb-3" style="color: var(--saas-text-primary) !important; border-bottom: 1px solid var(--saas-border) !important;">
                                <i data-lucide="handshake" class="saas-icon saas-icon-orange mr-2" style="vertical-align: middle;"></i> Manfaat Bergabung
                            </h4>
                            <ul class="list-unstyled text-muted pl-0" style="font-size: 14px; line-height: 2; color: var(--saas-text-secondary) !important;">
                                <li class="mb-3 d-flex align-items-start gap-2">
                                    <i data-lucide="check-circle-2" class="saas-icon saas-icon-orange" style="width: 16px; height: 16px; margin-top: 4px; flex-shrink: 0;"></i>
                                    <span><strong>Dashboard Spasial:</strong> Kelola data operasional, fasilitas, dan jadwal praktik faskes Anda kapan saja secara real-time.</span>
                                </li>
                                <li class="mb-3 d-flex align-items-start gap-2">
                                    <i data-lucide="check-circle-2" class="saas-icon saas-icon-orange" style="width: 16px; height: 16px; margin-top: 4px; flex-shrink: 0;"></i>
                                    <span><strong>Sertifikasi Wisata Sehat:</strong> Tingkatkan branding keselamatan medis objek wisata Anda untuk menarik minat pelancong nasional & asing.</span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <i data-lucide="check-circle-2" class="saas-icon saas-icon-orange" style="width: 16px; height: 16px; margin-top: 4px; flex-shrink: 0;"></i>
                                    <span><strong>Integrasi Sistem Rujukan:</strong> Membantu dinas terkait mendeteksi sebaran titik rawan kecelakaan atau kebutuhan faskes darurat di Subang.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Column Right: CTA Cards --}}
                <div class="col-md-6 mb-4 px-3" data-aos="fade-left" data-aos-duration="600">
                    <div class="saas-card p-5 h-100 text-center d-flex flex-column justify-content-center align-items-center" style="border: 1.5px solid var(--saas-orange) !important; background: rgba(255, 122, 0, 0.02) !important;">
                        <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; border-radius: 50%; background: rgba(255, 122, 0, 0.08);">
                            <i data-lucide="plus-circle" class="saas-icon saas-icon-orange" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h4 class="font-weight-bold mb-3" style="color: var(--saas-text-primary) !important; font-size: 1.6rem;">Siap Berkolaborasi?</h4>
                        <p class="text-muted mb-4" style="font-size: 14px; max-width: 320px; color: var(--saas-text-secondary) !important;">Daftarkan instansi faskes atau pengelola destinasi wisata Anda pada sistem GIS WanderMed sekarang.</p>
                        
                        <div class="w-100 d-flex flex-column gap-2" style="gap: 12px;">
                            <a href="/daftar" class="btn btn-saas-primary w-100 justify-content-center">
                                <i data-lucide="user-check"></i> Mulai Pendaftaran Mitra
                            </a>
                            @if(session('auth_user'))
                                <a href="{{ url('/login') }}" class="btn btn-saas-secondary w-100 justify-content-center">
                                    Lanjut ke Dashboard
                                </a>
                            @else
                                <a href="/login" class="btn btn-saas-secondary w-100 justify-content-center">
                                    Login Dashboard Mitra
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── 5. DEDIKASI SECTION ── --}}
    <section class="py-5 mb-4" style="background: var(--saas-slate) !important; border-top: 1px solid var(--saas-border) !important; transition: background 0.3s ease;">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-10 px-3" data-aos="zoom-in" data-aos-duration="800">
                    <div class="saas-card position-relative p-5 overflow-hidden text-center">
                        <div class="position-relative" style="z-index: 2;">
                            <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(10,20,40,0.04);">
                                <i data-lucide="quote" class="saas-icon saas-icon-orange"></i>
                            </div>
                            
                            <h4 class="font-weight-bold mb-4" style="color: var(--saas-text-primary) !important; letter-spacing: 0.5px; font-family:'Poppins', sans-serif;">Dedikasi Untuk Pariwisata Subang</h4>
                            
                            <p class="text-muted mx-auto mb-5" style="max-width: 720px; font-size: 1.05rem; line-height: 1.8; font-style: italic; color: var(--saas-text-secondary) !important;">
                                "Keamanan perjalanan Anda adalah fondasi utama dari sistem kami. WanderMed hadir menyajikan ekosistem tanggap darurat yang efisien, transparan, dan terintegrasi secara spasial demi melindungi setiap petualangan Anda di Kabupaten Subang."
                            </p>

                            <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 15px;">
                                <div class="d-flex align-items-center px-4 py-2" style="background: var(--saas-slate) !important; border-radius: 50px; border: 1px solid var(--saas-border) !important;">
                                    <i data-lucide="activity" class="saas-icon saas-icon-orange mr-2" style="width: 18px; height: 18px;"></i>
                                    <span style="font-weight: 700; font-size: 0.85rem; letter-spacing: 0.5px; color: var(--saas-text-primary) !important;">WANDERMED DEV LABS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="{{ asset('js/wisatawan-home.js') }}"></script>

@endpush
