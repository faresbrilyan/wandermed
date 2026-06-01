@extends('layouts.public')

@section('title', 'Panduan Integrasi Bot Telegram - WanderMed')

@push('styles')
<style>
    .panduan-hero {
        padding: 100px 0 60px;
        background: linear-gradient(135deg, rgba(17, 34, 64, 0.95), rgba(10, 20, 42, 0.98));
        position: relative;
        overflow: hidden;
        transition: background 0.3s ease;
    }
    .panduan-hero::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 122, 0, 0.15) 0%, transparent 70%);
        top: -100px;
        right: -100px;
        z-index: 1;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, border-color 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    .tab-btn {
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 6px;
    }
    .tab-btn.active {
        background: linear-gradient(135deg, var(--hnb-orange, #ff7a00), #ff5100);
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(255, 122, 0, 0.4);
    }
    .panduan-step {
        display: flex;
        align-items: flex-start;
        margin-bottom: 24px;
    }
    .panduan-step-number {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, var(--hnb-orange, #ff7a00), #ff5100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(255, 122, 0, 0.3);
        margin-right: 18px;
    }
    .faq-accordion .card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: background 0.3s ease, border-color 0.3s ease;
    }
    .faq-accordion .card-header {
        background: transparent;
        border-bottom: none;
        padding: 18px 24px;
    }
    .faq-accordion .btn-link {
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 0;
        text-align: left;
    }
    .faq-accordion .btn-link:hover {
        color: var(--hnb-orange, #ff7a00);
    }
    .faq-accordion .card-body {
        padding: 0 24px 20px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .code-box {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 10px 14px;
        font-family: monospace;
        color: #ff7a00;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.3s ease, border-color 0.3s ease;
    }

    /* Light Mode Overrides */
    body.light-mode .panduan-hero {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }
    body.light-mode .panduan-hero h1 {
        color: var(--hnb-navy, #112240) !important;
    }
    body.light-mode .panduan-hero p {
        color: #475569 !important;
    }
    body.light-mode .panduan-content-section {
        background: #f8fafc !important;
    }
    body.light-mode .panduan-content-section h3 {
        color: var(--hnb-navy, #112240) !important;
        border-bottom-color: rgba(0,0,0,0.06) !important;
    }
    body.light-mode .panduan-content-section h5 {
        color: var(--hnb-navy, #112240) !important;
    }
    body.light-mode .panduan-content-section p {
        color: #475569 !important;
    }
    body.light-mode .glass-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }
    body.light-mode .glass-card:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }
    body.light-mode .tab-btn {
        background: rgba(0, 0, 0, 0.05);
        color: #334155;
        border-color: rgba(0, 0, 0, 0.08);
    }
    body.light-mode .tab-btn.active {
        color: #ffffff;
    }
    body.light-mode .code-box {
        background: rgba(0, 0, 0, 0.04);
        border-color: rgba(0, 0, 0, 0.08);
        color: #ea580c;
    }
    body.light-mode .faq-accordion h3 {
        color: var(--hnb-navy, #112240) !important;
    }
    body.light-mode .faq-accordion .card {
        background: #ffffff;
        border-color: rgba(0, 0, 0, 0.06);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }
    body.light-mode .faq-accordion .btn-link {
        color: var(--hnb-navy, #112240) !important;
    }
    body.light-mode .faq-accordion .btn-link:hover {
        color: var(--hnb-orange, #ff7a00) !important;
    }
    body.light-mode .faq-accordion .btn-link i {
        color: #64748b !important;
    }
    body.light-mode .faq-accordion .card-body {
        color: #475569 !important;
    }
</style>
@endpush

@section('content')
    @include('partials.navbar')

    <!-- Hero Section -->
    <section class="panduan-hero">
        <div class="container px-4 text-center py-5" style="position: relative; z-index: 5;">
            <div class="icon-kesehatan mx-auto mb-4 d-flex align-items-center justify-content-center" 
                 style="width: 90px; height: 90px; background: linear-gradient(135deg, #0088cc, #005580); border-radius: 24px; box-shadow: 0 8px 24px rgba(0, 136, 204, 0.4);">
                <i class="fab fa-telegram-plane fa-3x text-white"></i>
            </div>
            <h1 class="font-weight-bold text-white mb-3" style="font-size: 2.5rem;">Panduan Pemulihan Akun via Telegram</h1>
            <p class="text-white-50 mx-auto" style="max-width: 650px; font-size: 1.1rem; line-height: 1.7;">
                Gunakan bot Telegram resmi WanderMed untuk mereset kata sandi Anda secara instan dan mandiri, baik untuk akun Wisatawan maupun Mitra Faskes.
            </p>
        </div>
    </section>

    <!-- Main Content Guide -->
    <section class="py-5 panduan-content-section" style="background: #0f172a; transition: background 0.3s ease;">
        <div class="container px-4">
            
            <!-- Account Type Selector -->
            <div class="d-flex justify-content-center gap-3 mb-5 flex-wrap">
                <button type="button" class="tab-btn active" onclick="switchGuide('wisatawan')">
                    <i class="fas fa-user-astronaut"></i> Akun Wisatawan
                </button>
                <button type="button" class="tab-btn" onclick="switchGuide('faskes')">
                    <i class="fas fa-clinic-medical"></i> Akun Mitra Faskes
                </button>
            </div>

            <div class="row">
                <!-- STEP 1: LINKING ACCOUNT -->
                <div class="col-lg-6 mb-4">
                    <div class="glass-card h-100 p-4 p-md-5">
                        <h3 class="font-weight-bold text-white mb-4" style="font-size: 1.6rem; border-bottom: 2px solid rgba(255,255,255,0.05); padding-bottom: 15px;">
                            <i class="fas fa-link text-hnb-orange mr-2"></i> Langkah 1: Hubungkan Akun
                        </h3>
                        
                        <!-- Tourist Guide Steps -->
                        <div id="guide-wisatawan-steps">
                            <div class="panduan-step">
                                <div class="panduan-step-number">1</div>
                                <div>
                                    <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Masuk ke Dashboard Wisatawan</h5>
                                    <p class="text-white-50 small mb-0">Login ke website WanderMed dengan akun Wisatawan Anda.</p>
                                </div>
                            </div>
                            <div class="panduan-step">
                                <div class="panduan-step-number">2</div>
                                <div>
                                    <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Buka Pengaturan Keamanan</h5>
                                    <p class="text-white-50 small mb-0">Pergi ke menu sidebar bagian bawah, lalu klik bagian integrasi Telegram.</p>
                                </div>
                            </div>
                            <div class="panduan-step">
                                <div class="panduan-step-number">3</div>
                                <div>
                                    <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Kirimkan Kode Verifikasi</h5>
                                    <p class="text-white-50 small mb-0">Salin kode verifikasi Anda (contoh: <code>WDM-XXXXXX</code>) lalu kirimkan ke bot Telegram dengan format:</p>
                                    <div class="code-box mt-2">/start KODE_VERIFIKASI</div>
                                </div>
                            </div>
                        </div>

                        <!-- Faskes Guide Steps -->
                        <div id="guide-faskes-steps" style="display: none;">
                            <div class="panduan-step">
                                <div class="panduan-step-number">1</div>
                                <div>
                                    <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Masuk ke Panel Kontrol Faskes</h5>
                                    <p class="text-white-50 small mb-0">Login ke website WanderMed sebagai Mitra Faskes.</p>
                                </div>
                            </div>
                            <div class="panduan-step">
                                <div class="panduan-step-number">2</div>
                                <div>
                                    <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Buka Profil & Lokasi</h5>
                                    <p class="text-white-50 small mb-0">Pergi ke menu profil, temukan bagian <b>Integrasi Bot Telegram</b> di bagian bawah halaman.</p>
                                </div>
                            </div>
                            <div class="panduan-step">
                                <div class="panduan-step-number">3</div>
                                <div>
                                    <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Hubungkan ke Bot</h5>
                                    <p class="text-white-50 small mb-0">Klik "Hubungkan Telegram" untuk generate kode, lalu kirim ke bot dengan perintah:</p>
                                    <div class="code-box mt-2">/start KODE_VERIFIKASI</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: RESETTING PASSWORD -->
                <div class="col-lg-6 mb-4">
                    <div class="glass-card h-100 p-4 p-md-5">
                        <h3 class="font-weight-bold text-white mb-4" style="font-size: 1.6rem; border-bottom: 2px solid rgba(255,255,255,0.05); padding-bottom: 15px;">
                            <i class="fas fa-key text-hnb-orange mr-2"></i> Langkah 2: Reset Password Mandiri
                        </h3>
                        <p class="text-white-50 small mb-4">
                            Setelah akun terhubung, Anda tidak perlu lagi menghubungi Admin secara manual untuk meminta penyetelan ulang kata sandi. Cukup lakukan langkah berikut:
                        </p>
                        
                        <div class="panduan-step">
                            <div class="panduan-step-number">1</div>
                            <div>
                                <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Buka Bot Telegram</h5>
                                <p class="text-white-50 small mb-0">
                                    Cari atau klik tautan bot Telegram resmi: 
                                    <a href="https://t.me/{{ env('TELEGRAM_BOT_USERNAME', 'wandermed_recovery_bot') }}" target="_blank" class="text-info font-weight-bold text-decoration-none">
                                        {{ '@' . env('TELEGRAM_BOT_USERNAME', 'wandermed_recovery_bot') }} <i class="fas fa-external-link-alt ml-1" style="font-size: 10px;"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="panduan-step">
                            <div class="panduan-step-number">2</div>
                            <div>
                                <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Kirim Perintah Reset</h5>
                                <p class="text-white-50 small mb-0">Ketik perintah `/reset` diikuti dengan email terdaftar Anda:</p>
                                <div class="code-box mt-2">/reset email_anda@gmail.com</div>
                            </div>
                        </div>
                        <div class="panduan-step">
                            <div class="panduan-step-number">3</div>
                            <div>
                                <h5 class="text-white font-weight-bold" style="font-size: 1.05rem;">Gunakan Password Sementara</h5>
                                <p class="text-white-50 small mb-0">Bot akan langsung merespons dengan mengirimkan kata sandi baru secara instan. Gunakan untuk masuk dan segera ubah kata sandi di dashboard demi keamanan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mt-5 max-width: 800px; mx-auto">
                <h3 class="text-center font-weight-bold text-white mb-4">Pertanyaan Umum (FAQ)</h3>
                
                <div class="faq-accordion" id="faqAccordion">
                    
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Apakah akun Faskes wajib menggunakan Telegram untuk reset sandi?
                                    <i class="fas fa-chevron-down text-white-50"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#faqAccordion">
                            <div class="card-body">
                                Sangat disarankan. Dengan menghubungkan akun Faskes Anda ke Telegram, Anda bisa memulihkan akses secara mandiri dalam hitungan detik tanpa harus menunggu admin menanggapi pesan/tiket bantuan Anda secara manual.
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Kenapa bot membalas "Email tidak terhubung dengan Telegram"?
                                    <i class="fas fa-chevron-down text-white-50"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordion">
                            <div class="card-body">
                                Ini berarti akun WanderMed Anda belum terhubung secara valid ke akun Telegram Anda. Silakan login terlebih dahulu ke website, buka menu pengaturan integrasi Telegram, dapatkan kode baru, dan ikuti panduan Langkah 1.
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Apakah saya harus menyambungkan kembali jika password sudah diubah?
                                    <i class="fas fa-chevron-down text-white-50"></i>
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordion">
                            <div class="card-body">
                                Tidak perlu. Hubungan akun WanderMed dan Telegram didasarkan pada ID Telegram Anda yang disimpan secara permanen di server kami. Anda tetap bisa menggunakan bot meskipun Anda sudah berkali-kali mengubah kata sandi di web.
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
<script>
    function switchGuide(type) {
        const btns = document.querySelectorAll('.tab-btn');
        btns.forEach(btn => btn.classList.remove('active'));
        
        if (type === 'wisatawan') {
            document.getElementById('guide-wisatawan-steps').style.display = 'block';
            document.getElementById('guide-faskes-steps').style.display = 'none';
            event.currentTarget.classList.add('active');
        } else {
            document.getElementById('guide-wisatawan-steps').style.display = 'none';
            document.getElementById('guide-faskes-steps').style.display = 'block';
            event.currentTarget.classList.add('active');
        }
    }
</script>
@endpush
