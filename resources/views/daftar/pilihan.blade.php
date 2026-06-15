@extends('layouts.public')

@section('content')

    @include('partials.navbar')

@push('styles')
    <link href="{{ asset('css/daftar-pilihan.css') }}" rel="stylesheet">
@endpush


    <section class="hero-slanted" style="min-height: 100vh; display: flex; align-items: center; padding-top: 50px; padding-bottom: 50px; clip-path: none;">
        <div class="container px-5" style="position: relative; z-index: 5;">

            <!-- Flash sukses -->
            @if(session('success'))
            <div class="alert mx-auto mb-4 d-flex align-items-start animate-fade-up" style="max-width: 750px; border-radius: 12px; background:rgba(40,167,69,0.15); border:1px solid rgba(40,167,69,0.4); color:#6fcf97; text-align: left;">
                <i class="fas fa-check-circle mr-3 mt-1 fa-lg"></i>
                <div class="small">{!! session('success') !!}</div>
            </div>
            @endif

            <!-- Header Title -->
            <div class="text-center mb-5 animate-fade-up">
                <div style="font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--hnb-orange); background: rgba(255, 122, 0, 0.08); padding: 6px 14px; border-radius: 50px; display: inline-block; margin-bottom: 16px;">
                    Portal Kemitraan WanderMed
                </div>
                <h2 class="font-weight-bold text-white teks-judul mb-3" style="font-size: 2.3rem; letter-spacing: -0.5px;">Mulai Kemitraan Bersama WanderMed</h2>
                <div style="width: 50px; height: 3px; background-color: var(--hnb-orange); margin: 15px auto; border-radius: 2px;"></div>
                <p class="text-white-50 teks-subjudul mx-auto" style="max-width: 650px; font-size: 15px; line-height: 1.7;">Bergabunglah dengan ekosistem kesehatan digital Subang. Tampilkan instansi Anda di peta medis publik, berikan rasa aman untuk pengunjung, dan tingkatkan visibilitas layanan Anda.</p>
            </div>

            <!-- Notice Banner -->
            <div class="animate-fade-up delay-1 mb-5" style="max-width: 750px; margin: 0 auto;">
                <div class="glass-premier radius-hnb p-3 shadow-sm d-flex align-items-center" style="background: rgba(255, 122, 0, 0.05) !important; border: 1px solid rgba(255, 122, 0, 0.15) !important; border-left: 4px solid var(--hnb-orange) !important;">
                    <div style="min-width: 45px; text-align: center;">
                        <i class="fas fa-info-circle fa-2x text-hnb-orange"></i>
                    </div>
                    <p class="text-white-50 mb-0" style="font-size: 13.5px; text-align: left; line-height: 1.6;">
                        Setiap jenis kemitraan memiliki formulir dan alur verifikasi yang berbeda. Silakan <strong class="text-white">pilih jenis kemitraan</strong> di bawah ini yang sesuai dengan layanan instansi Anda.
                    </p>
                </div>
            </div>

            <!-- Choice Cards Row -->
            <div class="row animate-fade-up delay-2 justify-content-center">
                <!-- Destinasi Pariwisata Card -->
                <div class="col-md-6 col-lg-5 mb-4">
                    <div class="card glass-premier partner-card p-4 border shadow-sm">
                        <div class="card-body p-0 d-flex flex-column justify-content-between" style="z-index: 2; height: 100%;">
                            <div>
                                <div class="partner-icon-wrapper" style="background: rgba(255, 122, 0, 0.1); color: var(--hnb-orange);">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <h4 class="font-weight-bold text-white mb-2" style="font-size: 1.4rem;">Destinasi Pariwisata</h4>
                                <p class="text-white-50 small mb-4" style="line-height: 1.6; font-size: 13px;">Tampilkan destinasi wisata Anda di peta digital WanderMed agar wisatawan dapat mengetahui lokasi wisata beserta ketersediaan fasilitas medis terdekat.</p>
                                
                                <div style="height: 1px; background: rgba(255,255,255,0.08); margin-bottom: 20px;"></div>
                                
                                <h6 class="font-weight-bold text-white mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">KEUNTUNGAN KEMITRAAN:</h6>
                                <ul class="list-unstyled mb-4" style="text-align: left;">
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Promosi lokasi pariwisata di peta publik
                                    </li>
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Akses laporan masalah langsung dari wisatawan
                                    </li>
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Edukasi pos kesehatan terdekat ke turis
                                    </li>
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Pendaftaran simpel dan tanpa perlu password
                                    </li>
                                </ul>
                            </div>
                            
                            <a href="/daftar/pariwisata" class="btn btn-primary btn-block radius-hnb py-3 font-weight-bold mt-2" style="background: linear-gradient(135deg, var(--hnb-orange) 0%, #ff5100 100%); border: none; box-shadow: 0 4px 15px rgba(255, 122, 0, 0.25);">
                                Mulai Pendaftaran Wisata <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mitra Faskes Card -->
                <div class="col-md-6 col-lg-5 mb-4">
                    <div class="card glass-premier partner-card partner-card-faskes p-4 border shadow-sm">
                        <div class="card-body p-0 d-flex flex-column justify-content-between" style="z-index: 2; height: 100%;">
                            <div>
                                <div class="partner-icon-wrapper" style="background: rgba(30, 144, 255, 0.1); color: #1e90ff;">
                                    <i class="fas fa-hospital-user"></i>
                                </div>
                                <h4 class="font-weight-bold text-white mb-2" style="font-size: 1.4rem;">Mitra Faskes</h4>
                                <p class="text-white-50 small mb-4" style="line-height: 1.6; font-size: 13px;">Daftarkan klinik, rumah sakit, puskesmas, apotek, atau dokter praktik mandiri Anda untuk menjadi rujukan medis utama wisatawan.</p>
                                
                                <div style="height: 1px; background: rgba(255,255,255,0.08); margin-bottom: 20px;"></div>
                                
                                <h6 class="font-weight-bold text-white mb-3" style="font-size: 0.85rem; letter-spacing: 0.5px;">KEUNTUNGAN KEMITRAAN:</h6>
                                <ul class="list-unstyled mb-4" style="text-align: left;">
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Menjadi rujukan utama rute darurat peta
                                    </li>
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Akses dashboard kelola status & BPJS
                                    </li>
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Update jadwal dokter & fasilitas instan
                                    </li>
                                    <li class="benefit-item text-white-50 small mb-2 d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success mr-2" style="font-size: 13px;"></i> Pantau umpan balik & ulasan wisatawan
                                    </li>
                                </ul>
                            </div>
                            
                            <a href="/daftar/faskes" class="btn btn-primary btn-block radius-hnb py-3 font-weight-bold mt-2" style="background: linear-gradient(135deg, #1e90ff 0%, #0056b3 100%); border: none; box-shadow: 0 4px 15px rgba(30, 144, 255, 0.25);">
                                Mulai Pendaftaran Faskes <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Steps Section -->
            <div class="text-center mt-5 mb-3 animate-fade-up delay-2">
                <h5 class="font-weight-bold text-white mb-4" style="letter-spacing: 1.5px; font-size: 1rem; text-transform: uppercase;">3 Langkah Mudah Kemitraan</h5>
                <div class="row justify-content-center">
                    <div class="col-md-4 mb-3">
                        <div class="step-card">
                            <div class="step-number mx-auto">1</div>
                            <h6 class="font-weight-bold text-white mb-2" style="font-size: 14.5px;">Pilih Jenis Kemitraan</h6>
                            <p class="text-white-50 small mb-0" style="font-size: 12.5px; line-height: 1.5;">Tentukan kategori layanan instansi Anda (Destinasi Wisata atau Fasilitas Kesehatan).</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="step-card">
                            <div class="step-number mx-auto">2</div>
                            <h6 class="font-weight-bold text-white mb-2" style="font-size: 14.5px;">Lengkapi Formulir</h6>
                            <p class="text-white-50 small mb-0" style="font-size: 12.5px; line-height: 1.5;">Isi data operasional, kontak penanggung jawab, koordinat lokasi GPS, dan dokumen pendukung.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="step-card">
                            <div class="step-number mx-auto">3</div>
                            <h6 class="font-weight-bold text-white mb-2" style="font-size: 14.5px;">Verifikasi Admin & Live</h6>
                            <p class="text-white-50 small mb-0" style="font-size: 12.5px; line-height: 1.5;">Tim WanderMed melakukan validasi data. Setelah disetujui, layanan Anda langsung aktif di peta digital.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action Links -->
            <div class="text-center mt-5 animate-fade-up delay-3">
                <a href="/" class="btn btn-outline-light radius-hnb px-5 py-3 font-weight-bold shadow-sm mb-3">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
                <div class="mt-2">
                    <p class="text-white-50 small">
                        Sudah punya akun? 
                        <a href="/login" class="text-hnb-orange font-weight-bold text-decoration-none ml-1">
                            Masuk Sekarang <i class="fas fa-sign-in-alt ml-1"></i>
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </section>

    <section class="bg-hnb-navy pt-5 pb-4 mt-5">
        @include('partials.footer')
    </section>

@endsection

