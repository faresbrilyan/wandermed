{{-- ============================================================
     Dashboard Mitra Faskes – WanderMed
     Layout: layouts/faskes/main.blade.php (Kustom, no SB Admin)
     ============================================================ --}}
@extends('layouts.faskes.main')

@push('styles')
<link href="{{ asset('css/doctor-schedule.css') }}" rel="stylesheet">
@endpush

@section('content')

{{-- Data URLs untuk dashboard-faskes.js (menghindari Blade route helper di file .js statis) --}}
<div id="faskesApp"
    data-url-status="{{ route('faskes.status.update') }}"
    data-url-fasilitas="{{ route('faskes.fasilitas.update') }}"
    style="display:contents;">

<!-- SESSION ALERT -->
@if(session('success'))
<div class="wm-alert success mb-3" style="background: rgba(28,200,138,0.12); border-left: 4px solid #1cc88a; padding: 12px 18px; border-radius: 8px; color: #1cc88a; font-size:13px;">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="wm-alert danger mb-3" style="background: rgba(231,74,59,0.12); border-left: 4px solid #e74a3b; padding: 12px 18px; border-radius: 8px; color: #e74a3b; font-size:13px;">
    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
</div>
@endif

<!-- ===== SECTION 1: DASHBOARD UTAMA ===== -->
<div id="sectionDashboard" class="faskes-section">

    <!-- Page Header -->
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Panel Kontrol Faskes</div>
            <div class="wm-page-subtitle">Perbarui status real-time agar wisatawan mendapat informasi akurat di peta</div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="wm-stat-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="wm-stat-card blue">
            <div class="wm-stat-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="wm-stat-value">{{ $totalPengunjung ?? 0 }}</div>
                <div class="wm-stat-label">Total Pengunjung</div>
            </div>
        </div>
        <div class="wm-stat-card yellow">
            <div class="wm-stat-icon"><i class="fas fa-bullhorn"></i></div>
            <div>
                <div class="wm-stat-value" style="font-size:14px;">
                    {{ $faskes && $faskes->status_operasional == 'open' ? 'BUKA' : 'TUTUP' }}
                </div>
                <div class="wm-stat-label">Status Operasional</div>
            </div>
        </div>
        <div class="wm-stat-card green">
            <div class="wm-stat-icon"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="wm-stat-value">{{ $totalUlasan ?? 0 }}</div>
                <div class="wm-stat-label">Total Ulasan Masuk</div>
            </div>
        </div>
    </div>

    {{-- Rating Summary Card --}}
    @php
        $rataRating = isset($ulasans) && $ulasans->count() > 0 ? round($ulasans->avg('rating'), 1) : 0;
        $persen = $rataRating > 0 ? ($rataRating / 5) * 100 : 0;
        $totalUlasanCount = $totalUlasan ?? 0;
    @endphp
    <div class="wm-card" style="border-left: 4px solid #f6c23e; margin-bottom: 22px;">
        <div class="wm-card-body" style="padding: 16px 22px; display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
            <div style="text-align:center; min-width:80px;">
                <div style="font-size:2.8rem; font-weight:800; color:#f6c23e; line-height:1;">{{ $rataRating > 0 ? $rataRating : '–' }}</div>
                <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">rata-rata</div>
            </div>
            <div style="flex:1; min-width:160px;">
                <div style="margin-bottom:6px;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star" style="font-size:16px; {{ $i <= round($rataRating) ? 'color:#f6c23e;' : 'color:rgba(255,255,255,0.15);' }}"></i>
                    @endfor
                </div>
                <div style="background:rgba(255,255,255,0.06); border-radius:20px; height:8px; overflow:hidden; margin-bottom:6px;">
                    <div style="background:linear-gradient(90deg,#f6c23e,#e5a800); height:100%; width:{{ $persen }}%; border-radius:20px;"></div>
                </div>
                <div style="font-size:12px; color:var(--text-muted);">
                    Berdasarkan <strong style="color:var(--text-primary);">{{ $totalUlasanCount }}</strong> ulasan wisatawan
                </div>
            </div>
            @if($totalUlasanCount > 0 && isset($ulasans))
            <div style="display:grid; gap:4px; min-width:130px;">
                @for($b = 5; $b >= 1; $b--)
                @php $cnt = $ulasans->where('rating', $b)->count(); $pct = $totalUlasanCount > 0 ? ($cnt / $totalUlasanCount) * 100 : 0; @endphp
                <div style="display:flex; align-items:center; gap:6px; font-size:10px;">
                    <span style="color:#f6c23e; width:10px; text-align:right;">{{ $b }}</span>
                    <i class="fas fa-star" style="font-size:8px; color:#f6c23e;"></i>
                    <div style="flex:1; background:rgba(255,255,255,0.06); border-radius:10px; height:5px; overflow:hidden;">
                        <div style="background:#f6c23e; height:100%; width:{{ $pct }}%;"></div>
                    </div>
                    <span style="color:var(--text-muted); width:16px;">{{ $cnt }}</span>
                </div>
                @endfor
            </div>
            @endif
        </div>
    </div>

    <!-- Pesan dari Admin (tampil hanya jika ada pesan) -->

    @if(!empty($faskes?->pesan_admin))
    <div class="wm-card mt-4" style="border-left: 4px solid #f6c23e; background: rgba(246,194,62,0.06);">
        <div class="wm-card-header" style="border-bottom: 1px solid rgba(246,194,62,0.2);">
            <div class="wm-card-title">
                <i class="fas fa-envelope-open-text" style="color: #f6c23e;"></i>
                Pesan dari Admin
                <span class="wm-badge yellow" style="margin-left: 8px; font-size: 10px; animation: pulse 2s infinite;">Baru</span>
            </div>
        </div>
        <div class="wm-card-body" style="padding: 16px 22px;">
            <p style="font-size: 14px; line-height: 1.6; margin: 0; color: var(--text-secondary);">
                {{ $faskes?->pesan_admin }}
            </p>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 10px;">
                <i class="fas fa-clock"></i> Dikirim oleh Administrator WanderMed
            </div>
        </div>
    </div>
    @endif

    <!-- Info ringkas faskes -->
    <div class="wm-card mt-4">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-info-circle"></i> Info Singkat Faskes</div>
            <button class="wm-btn ghost sm" onclick="switchSection('navProfilFaskes', 'sectionProfil')">
                <i class="fas fa-edit"></i> Edit Profil
            </button>
        </div>
        <div class="wm-card-body">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size:13px;">
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Nama Faskes</div>
                    <div style="font-weight:600;">{{ $faskes->nama_faskes ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Kategori</div>
                    <div style="font-weight:600;">{{ $faskes->jenis_faskes ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Alamat</div>
                    <div style="font-weight:600;">{{ $faskes->alamat ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">No. Telepon</div>
                    <div style="font-weight:600;">{{ $faskes->no_telp ?? '-' }}</div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">Koordinat</div>
                    <div style="font-weight:600; font-family: monospace;">
                        {{ $faskes->latitude ?? '0' }}, {{ $faskes->longitude ?? '0' }}
                    </div>
                </div>
                <div>
                    <div style="color: var(--text-muted); margin-bottom:2px;">BPJS</div>
                    <div>
                        @if($faskes && $faskes->dukungan_bpjs)
                            <span class="wm-badge green"><i class="fas fa-check"></i> Diterima</span>
                        @else
                            <span class="wm-badge danger"><i class="fas fa-times"></i> Tidak</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- End sectionDashboard -->

<!-- ===== SECTION 2: KONTROL STATUS ===== -->
<div id="sectionKontrolStatus" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Kontrol Status Real-time</div>
            <div class="wm-page-subtitle">Perubahan langsung tampil di peta publik WanderMed</div>
        </div>
        <span id="lastUpdatedLabel" style="font-size: 11px; color: var(--text-muted);">
            <i class="fas fa-clock mr-1"></i> Belum diperbarui
        </span>
    </div>

    <div class="wm-card">
        <div class="wm-card-body">
            <!-- Kategori 1: Status & Jam Operasional -->
            <div style="border-bottom: 1px solid var(--border); padding-bottom: 18px; margin-bottom: 18px;">
                <h5 style="font-size: 14px; font-weight: 600; color: var(--orange); margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-clock"></i> Status Operasional & Waktu Buka
                </h5>
                
                <!-- Toggle: Operasional -->
                <div class="wm-toggle-row">
                    <div class="wm-toggle-info">
                        <h6>Status Operasional Klinik</h6>
                        <p>Apakah faskes sedang buka dan dapat menerima pasien?</p>
                    </div>
                    <div class="wm-toggle-group">
                        <span class="wm-toggle-label" id="labelOps" style="color: {{ $faskes && $faskes->status_operasional == 'open' ? '#1cc88a' : '#e74a3b' }};">
                            {{ $faskes && $faskes->status_operasional == 'open' ? '✓ BUKA' : '✕ TUTUP' }}
                        </span>
                        <label class="wm-switch">
                            <input type="checkbox" id="switchOps" {{ $faskes && $faskes->status_operasional == 'open' ? 'checked' : '' }}
                                onchange="handleAjaxToggle('status_operasional', this.checked ? 'true' : 'false', 'switchOps', 'labelOps', '✓ BUKA', '✕ TUTUP', '#1cc88a', '#e74a3b', 'Status Operasional diperbarui!')">
                            <span class="wm-switch-slider"></span>
                        </label>
                    </div>
                </div>
                
                <!-- Toggle: Buka 24 Jam -->
                <div class="wm-toggle-row" style="margin-top:16px;">
                    <div class="wm-toggle-info">
                        <h6>Buka 24 Jam nonstop</h6>
                        <p>Apakah faskes ini beroperasi 24 jam penuh tanpa tutup?</p>
                    </div>
                    <div class="wm-toggle-group">
                        <span class="wm-toggle-label" id="label24Jam" style="color: {{ $faskes && $faskes->is_24_jam ? '#1cc88a' : '#858796' }};">
                            {{ $faskes && $faskes->is_24_jam ? '✓ YA (24 JAM)' : '✕ TIDAK' }}
                        </span>
                        <label class="wm-switch">
                            <input type="checkbox" id="switch24Jam" {{ $faskes && $faskes->is_24_jam ? 'checked' : '' }}
                                onchange="handleAjaxToggle('is_24_jam', this.checked ? '1' : '0', 'switch24Jam', 'label24Jam', '✓ YA (24 JAM)', '✕ TIDAK', '#1cc88a', '#858796', 'Status 24 Jam diperbarui!')">
                            <span class="wm-switch-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Jam Operasional -->
                <div style="margin-top:20px; background: rgba(255,255,255,0.02); border-radius: 8px; padding: 14px 18px; border: 1px solid var(--border);">
                    <h6 style="font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px;"><i class="far fa-clock"></i> Pengaturan Jam Operasional Manual</h6>
                    <p style="font-size: 11.5px; color: var(--text-muted); margin-bottom: 12px;">Hanya berlaku jika status faskes di atas TIDAK diatur Buka 24 Jam.</p>
                    <div style="display:flex; gap:16px; flex-wrap: wrap;">
                        <div>
                            <label style="font-size:12px; font-weight:600; color:var(--text-secondary); display:block; margin-bottom:4px;">Jam Buka</label>
                            <input type="time" class="wm-input" style="width:130px; height: 36px; padding: 6px 12px;" value="{{ $faskes && $faskes->jam_buka ? substr($faskes->jam_buka, 0, 5) : '' }}"
                                onchange="handleAjaxToggle('jam_buka', this.value, null, null, null, null, null, null, 'Jam Buka diperbarui!')">
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600; color:var(--text-secondary); display:block; margin-bottom:4px;">Jam Tutup</label>
                            <input type="time" class="wm-input" style="width:130px; height: 36px; padding: 6px 12px;" value="{{ $faskes && $faskes->jam_tutup ? substr($faskes->jam_tutup, 0, 5) : '' }}"
                                onchange="handleAjaxToggle('jam_tutup', this.value, null, null, null, null, null, null, 'Jam Tutup diperbarui!')">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kategori 2: Siaran Pengumuman Darurat -->
            <div>
                <h5 style="font-size: 14px; font-weight: 600; color: var(--orange); margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-broadcast-tower"></i> Pengumuman Sementara / Siaran Peta
                </h5>
                <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 14px;">
                    Tulis pengumuman mendesak atau catatan operasional sementara yang akan langsung dibaca wisatawan di pin peta Anda.
                </p>
                <div class="wm-form-group">
                    <textarea class="wm-textarea" id="inputPengumuman" rows="3" placeholder="Contoh: Stok oksigen terbatas hari ini, harap hubungi kami terlebih dahulu..." maxlength="200">{{ $faskes ? $faskes->pengumuman : '' }}</textarea>
                </div>
                <button class="wm-btn orange" style="width:100%; margin-top: 10px;" onclick="savePengumuman()">
                    <i class="fas fa-broadcast-tower"></i> Simpan & Siaran ke Peta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== SECTION 3: FASILITAS ===== -->
<div id="sectionFasilitas" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Manajemen Fasilitas & Layanan</div>
            <div class="wm-page-subtitle">Centang fasilitas yang aktif tersedia—akan langsung tampil di popup peta</div>
        </div>
    </div>

    <div class="wm-card">
        <div class="wm-card-body">
            @php
                $fasilitas_list = $faskes ? ($faskes->layanan_tersedia ?? []) : [];
                $icons = [
                    'UGD 24 Jam'     => ['icon' => 'fa-ambulance',    'color' => '#e74a3b'],
                    'Ambulans'       => ['icon' => 'fa-car',          'color' => '#4e73df'],
                    'Rawat Inap'     => ['icon' => 'fa-bed',          'color' => '#36b9cc'],
                    'Apotek'         => ['icon' => 'fa-pills',        'color' => '#1cc88a'],
                    'Laboratorium'   => ['icon' => 'fa-flask',        'color' => '#f6c23e'],
                    'Dok. Spesialis' => ['icon' => 'fa-user-md',      'color' => 'var(--orange)'],
                    'Poli Anak'      => ['icon' => 'fa-baby',         'color' => '#e74a3b'],
                    'Poli Gigi'      => ['icon' => 'fa-tooth',        'color' => '#4e73df'],
                    'Poli Umum'      => ['icon' => 'fa-stethoscope',  'color' => '#1cc88a'],
                    'Imunisasi'      => ['icon' => 'fa-syringe',      'color' => '#36b9cc'],
                    'Fisioterapi'    => ['icon' => 'fa-hand-holding-heart', 'color' => '#e74a3b'],
                    'Radiologi'      => ['icon' => 'fa-x-ray',        'color' => '#6f42c1'],
                    'Poli Bedah'          => ['icon' => 'fa-scissors',         'color' => '#f6c23e'],
                    'Poli Penyakit Dalam'  => ['icon' => 'fa-stethoscope',      'color' => '#858796'],
                    'Poli Kandungan'       => ['icon' => 'fa-female',           'color' => '#e83e8c'],
                ];
            @endphp
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 18px;">
                Centang fasilitas yang <strong style="color: #1cc88a;">aktif tersedia</strong> di faskes Anda hari ini.
            </p>
            <div class="wm-check-grid" id="fasilitasGrid">
                @foreach($icons as $fName => $props)
                @php $isChecked = in_array($fName, $fasilitas_list); @endphp
                <div class="wm-check-item {{ $isChecked ? 'checked' : '' }}" onclick="toggleCheck(this)">
                    <div class="wm-check-box"><i class="fas fa-check"></i></div>
                    <span class="wm-check-label"><i class="fas {{ $props['icon'] }} mr-1" style="color: {{ $props['color'] }};"></i> {{ $fName }}</span>
                    <input type="checkbox" value="{{ $fName }}" {{ $isChecked ? 'checked' : '' }}>
                </div>
                @endforeach
            </div>

            <button class="wm-btn success" style="width:100%; margin-top: 16px;" onclick="saveFasilitas()">
                <i class="fas fa-check-double"></i> Simpan Fasilitas ke Peta
            </button>
        </div>
    </div>
</div>

<!-- ===== SECTION JADWAL PRAKTIK ===== -->
<div id="sectionJadwal" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Manajemen Jadwal Praktik</div>
            <div class="wm-page-subtitle">Atur jadwal dokter yang tersedia di faskes Anda</div>
        </div>
        @if(isset($jadwals) && $jadwals->isNotEmpty())
            @php
                $lastUpdatedJadwal = $jadwals->max('updated_at');
            @endphp
            @if($lastUpdatedJadwal)
            <span style="font-size: 11.5px; color: var(--text-muted); background: rgba(0,0,0,0.03); padding: 4px 10px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);">
                <i class="fas fa-history mr-1" style="color: #f6c23e;"></i> Terakhir diubah: {{ \Carbon\Carbon::parse($lastUpdatedJadwal)->locale('id')->diffForHumans() }}
            </span>
            @endif
        @endif
    </div>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="wm-card">
                <div class="wm-card-header">
                    <div class="wm-card-title">
                        <i class="fas fa-calendar-plus mr-2" style="color: var(--orange);"></i> Tambah Jadwal Baru
                    </div>
                </div>
                <div class="wm-card-body" style="padding: 24px 28px;">
                    <form action="{{ route('faskes.jadwal.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="wm-label-modern">Nama Dokter</label>
                                <div class="wm-input-wrapper">
                                    <i class="fas fa-user-md input-icon"></i>
                                    <input type="text" name="nama_dokter" class="wm-input-modern" placeholder="Nama Lengkap Dokter" required maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="wm-label-modern">Spesialisasi / Poli</label>
                                <div class="wm-input-wrapper">
                                    <i class="fas fa-stethoscope input-icon"></i>
                                    <input type="text" name="spesialisasi" class="wm-input-modern" placeholder="Misal: Poli Anak, Poli Gigi, Poli Umum" required maxlength="100">
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Hari Praktik (Dedicated full row, highly visible & spacious) -->
                        <div class="row mt-2">
                            <div class="col-12 mb-3">
                                <label class="wm-label-modern">Hari Praktik</label>
                                <div class="wm-days-selector">
                                    @foreach([
                                        'Senin' => 'Sen', 
                                        'Selasa' => 'Sel', 
                                        'Rabu' => 'Rab', 
                                        'Kamis' => 'Kam', 
                                        'Jumat' => 'Jum', 
                                        'Sabtu' => 'Sab', 
                                        'Minggu' => 'Min'
                                    ] as $fullName => $shortName)
                                    <label class="wm-day-pill">
                                        <input type="checkbox" name="hari[]" value="{{ $fullName }}">
                                        <span class="day-text">{{ $shortName }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Jam Mulai, Jam Selesai, dan Tombol Submit (Perfect horizontal alignment) -->
                        <div class="row align-items-end mt-2">
                            <div class="col-md-4 mb-3">
                                <label class="wm-label-modern">Jam Mulai</label>
                                <div class="wm-input-wrapper">
                                    <input type="time" name="jam_mulai" class="wm-input-modern-noicon" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="wm-label-modern">Jam Selesai</label>
                                <div class="wm-input-wrapper">
                                    <input type="time" name="jam_selesai" class="wm-input-modern-noicon" required>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="submit" class="wm-btn-premium" style="height: 42px; display: inline-flex; align-items: center; justify-content: center; width: 100%;">
                                    <i class="fas fa-plus"></i> Tambah Jadwal Praktik
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="wm-card">
                <div class="wm-card-header" style="display:flex; justify-content:space-between; align-items:center;">
                    <div class="wm-card-title">
                        <i class="fas fa-list-ul mr-2" style="color: var(--orange);"></i> Daftar Jadwal Praktik
                    </div>
                    <span class="badge" style="background: rgba(255,122,0,0.1); color: var(--orange); font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 12px;">
                        {{ count($jadwals ?? []) }} Dokter Aktif
                    </span>
                </div>
                
                @if(empty($jadwals) || $jadwals->isEmpty())
                    <div class="text-center py-5" style="color: var(--text-muted);">
                        <div style="background: rgba(0,0,0,0.02); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                            <i class="fas fa-calendar-times fa-2x" style="opacity: 0.3; color: var(--text-muted);"></i>
                        </div>
                        <div class="font-weight-bold" style="font-size: 14px; color: var(--text-primary);">Belum Ada Jadwal</div>
                        <div style="font-size: 12px; margin-top: 4px;">Tambahkan jadwal dokter menggunakan form di atas.</div>
                    </div>
                @else
                    <div class="wm-table-wrap" style="border-radius: 0 0 16px 16px; overflow: hidden; border-top: 1px solid var(--border);">
                        <table class="wm-table-modern">
                            <thead>
                                <tr>
                                    <th>Dokter & Spesialisasi</th>
                                    <th>Hari Praktik</th>
                                    <th>Jam Kerja</th>
                                    <th style="text-align: right; padding-right: 24px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwals as $jadwal)
                                @php
                                    $doctorName = $jadwal->nama_dokter;
                                    $initial = strtoupper(substr($doctorName, 0, 1));
                                    // Generate a stable aesthetic gradient color based on doctor name
                                    $hash = md5($doctorName);
                                    $colorIndex = hexdec(substr($hash, 0, 2)) % 4;
                                    $gradients = [
                                        'linear-gradient(135deg, #4e73df, #224abe)', // Blue
                                        'linear-gradient(135deg, #1cc88a, #13855c)', // Emerald
                                        'linear-gradient(135deg, #36b9cc, #258391)', // Teal
                                        'linear-gradient(135deg, #e83e8c, #ab1859)'  // Pink/Kandungan style
                                    ];
                                    $gradient = $gradients[$colorIndex];
                                    $activeDays = is_array($jadwal->hari) ? $jadwal->hari : [$jadwal->hari];
                                @endphp
                                <tr class="wm-table-row-modern">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div class="doctor-avatar" style="background: {{ $gradient }};">
                                                {{ $initial }}
                                            </div>
                                            <div>
                                                <div class="doctor-name">{{ $doctorName }}</div>
                                                <span class="doctor-specialty">{{ $jadwal->spesialisasi }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="weekly-mini-calendar">
                                            @foreach([
                                                'Senin' => 'Sen', 
                                                'Selasa' => 'Sel', 
                                                'Rabu' => 'Rab', 
                                                'Kamis' => 'Kam', 
                                                'Jumat' => 'Jum', 
                                                'Sabtu' => 'Sab', 
                                                'Minggu' => 'Min'
                                            ] as $fullName => $shortName)
                                                @php
                                                    $isActive = false;
                                                    foreach($activeDays as $ad) {
                                                        if (strcasecmp($ad, $fullName) === 0) {
                                                            $isActive = true;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <span class="mini-day-dot {{ $isActive ? 'active' : '' }}" title="{{ $fullName }}">
                                                    {{ $shortName }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="schedule-time-badge">
                                            <i class="far fa-clock clock-icon"></i>
                                            {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                        </div>
                                    </td>
                                    <td style="text-align: right; padding-right: 24px;">
                                        <form action="{{ route('faskes.jadwal.destroy', $jadwal->id) }}" method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="wm-action-delete" title="Hapus Jadwal" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal untuk {{ $doctorName }}?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ===== SECTION ULASAN WISATAWAN ===== -->
<div id="sectionUlasan" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Ulasan & Feedback Wisatawan</div>
            <div class="wm-page-subtitle">Baca dan balas ulasan dari wisatawan yang telah berkunjung</div>
        </div>
    </div>
    <div class="wm-card">
        <div class="wm-card-body" style="padding: 0;">
            @forelse($ulasans ?? [] as $ulasan)
            @php
                $reviewer = $ulasan->user;
                $hasAlergi = $reviewer && !empty($reviewer->riwayat_alergi);
                $hasPenyakit = $reviewer && !empty($reviewer->riwayat_penyakit);
                $hasGolDarah = $reviewer && !empty($reviewer->gol_darah);
                $initial = $reviewer ? strtoupper(substr($reviewer->name, 0, 1)) : '?';
            @endphp
            <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05);">

                {{-- Header: Avatar + Nama + Bintang + Tanggal --}}
                <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:12px;">
                    <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg,#4e73df,#224abe); display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff; font-size:16px; flex-shrink:0;">
                        {{ $initial }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                            <div>
                                <strong style="font-size:14px;">{{ $reviewer->name ?? 'Wisatawan' }}</strong>
                                <span style="margin-left:8px; color:#f6c23e;">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="fas fa-star" style="font-size:11px; {{ $i <= $ulasan->rating ? 'color:#f6c23e;' : 'color:rgba(255,255,255,0.15);' }}"></i>
                                    @endfor
                                    <span style="font-size:11px; color:var(--text-muted); margin-left:4px;">({{ $ulasan->rating }}/5)</span>
                                </span>
                            </div>
                            <small style="color:var(--text-muted); font-size:11px; white-space:nowrap;">
                                <i class="fas fa-clock mr-1"></i>{{ $ulasan->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>

                        {{-- Medical Info Chips --}}
                        @if($hasGolDarah || $hasAlergi || $hasPenyakit)
                        <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:6px;">
                            @if($hasGolDarah)
                            <span style="display:inline-flex; align-items:center; gap:4px; background:rgba(231,74,59,0.1); color:#e74a3b; border:1px solid rgba(231,74,59,0.3); border-radius:20px; padding:2px 9px; font-size:10px; font-weight:700;">
                                <i class="fas fa-tint" style="font-size:9px;"></i> Gol. Darah: {{ $reviewer->gol_darah }}
                            </span>
                            @endif
                            @if($hasAlergi)
                            <span style="display:inline-flex; align-items:center; gap:4px; background:rgba(246,194,62,0.1); color:#f6c23e; border:1px solid rgba(246,194,62,0.3); border-radius:20px; padding:2px 9px; font-size:10px; font-weight:600;" title="{{ $reviewer->riwayat_alergi }}">
                                <i class="fas fa-exclamation-triangle" style="font-size:9px;"></i>
                                Alergi: {{ Str::limit($reviewer->riwayat_alergi, 50) }}
                            </span>
                            @endif
                            @if($hasPenyakit)
                            <span style="display:inline-flex; align-items:center; gap:4px; background:rgba(54,185,204,0.1); color:#36b9cc; border:1px solid rgba(54,185,204,0.3); border-radius:20px; padding:2px 9px; font-size:10px; font-weight:600;" title="{{ $reviewer->riwayat_penyakit }}">
                                <i class="fas fa-notes-medical" style="font-size:9px;"></i>
                                Riwayat Penyakit: {{ Str::limit($reviewer->riwayat_penyakit, 50) }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Komentar --}}
                <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:12px 14px; font-size:13px; color:var(--text-secondary); line-height:1.6; margin-bottom:12px; border-left:3px solid rgba(255,255,255,0.1);">
                    "{{ $ulasan->komentar }}"
                </div>

                {{-- Balasan dihapus sesuai request user (tidak perlu fitur balas) --}}
            </div>
            @empty
            <div class="text-center py-5" style="color:var(--text-muted);">
                <i class="fas fa-comment-slash fa-2x mb-3 d-block" style="opacity:0.3;"></i>
                Belum ada ulasan masuk dari wisatawan.
            </div>
            @endforelse
        </div>
    </div>
</div>


<!-- ===== SECTION 4: PROFIL FASKES ===== -->
<div id="sectionProfil" class="faskes-section" style="display:none;">
    <div class="wm-page-header">
        <div>
            <div class="wm-page-title">Profil & Lokasi Faskes</div>
            <div class="wm-page-subtitle">Perubahan akan langsung diperbarui di sistem peta WanderMed</div>
        </div>
    </div>

    <div class="wm-card">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-hospital"></i> Informasi & Lokasi Faskes</div>
        </div>
        <div class="wm-card-body">
            <form action="{{ route('faskes.profil.update') }}" method="POST">
                @csrf
                
                <!-- Kategori 1: Identitas Dasar -->
                <div style="border-bottom: 1px solid var(--border); padding-bottom: 18px; margin-bottom: 18px;">
                    <h5 style="font-size: 14px; font-weight: 600; color: var(--orange); margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-id-card"></i> Identitas Faskes
                    </h5>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 18px;">
                        <div class="wm-form-group" style="grid-column: 1/-1;">
                            <label class="wm-label">Nama Faskes <span style="color:#e74a3b">*</span></label>
                            <input type="text" name="nama_faskes" class="wm-input" value="{{ $faskes?->nama_faskes ?? '' }}" placeholder="Contoh: RSUD Subang" required maxlength="100">
                        </div>
                        <div class="wm-form-group">
                            <label class="wm-label">Kategori / Jenis <span style="color:#e74a3b">*</span></label>
                            <select name="jenis_faskes" class="wm-input" required>
                                @foreach(['Rumah Sakit','Klinik','Apotek','Puskesmas','Lainnya'] as $jenis)
                                <option value="{{ $jenis }}" {{ ($faskes?->jenis_faskes ?? '') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="wm-form-group">
                            <label class="wm-label">Nomor Telepon</label>
                            <input type="tel" name="no_telp" class="wm-input" value="{{ $faskes?->no_telp ?? '' }}" placeholder="0260-xxxxxx" maxlength="15">
                        </div>
                        <div class="wm-form-group" style="grid-column: 1/-1;">
                            <label class="wm-label">Alamat Lengkap <span style="color:#e74a3b">*</span></label>
                            <textarea name="alamat" class="wm-textarea" rows="2" required maxlength="200">{{ $faskes?->alamat ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Kategori 2: Koordinat Lokasi Peta -->
                <div style="border-bottom: 1px solid var(--border); padding-bottom: 18px; margin-bottom: 18px;">
                    <h5 style="font-size: 14px; font-weight: 600; color: var(--orange); margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-map-marker-alt"></i> Koordinat Lokasi di Peta
                    </h5>
                    
                    <div style="background: rgba(246,194,62,0.08); border: 1px solid rgba(246,194,62,0.3); border-radius:8px; padding:12px; margin-bottom:16px; font-family:monospace; font-size:14px; text-align:center;">
                        📍 Lat Saat Ini: <strong>{{ $faskes?->latitude ?? '0.000000' }}</strong> &nbsp;|&nbsp; Lng Saat Ini: <strong>{{ $faskes?->longitude ?? '0.000000' }}</strong>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                        <div class="wm-form-group">
                            <label class="wm-label">Latitude <span style="color:#e74a3b">*</span></label>
                            <input type="number" step="any" name="latitude" id="quickLat" class="wm-input" value="{{ $faskes?->latitude ?? '' }}" placeholder="-6.571..." required>
                        </div>
                        <div class="wm-form-group">
                            <label class="wm-label">Longitude <span style="color:#e74a3b">*</span></label>
                            <input type="number" step="any" name="longitude" id="quickLng" class="wm-input" value="{{ $faskes?->longitude ?? '' }}" placeholder="107.760..." required>
                        </div>
                    </div>
                    
                    <div style="margin-top: 12px; display:flex; gap:12px;">
                        <button type="button" class="wm-btn blue" onclick="fillGPS()" style="flex:1;">
                            <i class="fas fa-crosshairs"></i> Deteksi Otomatis via GPS
                        </button>
                    </div>
                    
                    <div style="margin-top:14px; padding:10px 14px; background: rgba(255,255,255,0.03); border-radius:8px; font-size:11.5px; color: var(--text-muted); line-height: 1.5;">
                        <i class="fas fa-info-circle mr-1" style="color:#4e73df"></i>
                        <strong>Tips:</strong> Buka Google Maps, klik kanan di titik lokasi faskes Anda, lalu salin koordinatnya ke sini.
                    </div>
                </div>

                <!-- Kategori 3: Layanan BPJS Kesehatan -->
                <div style="margin-bottom: 24px;">
                    <h5 style="font-size: 14px; font-weight: 600; color: var(--orange); margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-notes-medical"></i> Layanan BPJS Kesehatan
                    </h5>
                    <label class="wm-label">Apakah faskes Anda menerima pasien peserta BPJS?</label>
                    <div style="display:flex; gap: 24px; margin-top: 8px;">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px;">
                            <input type="radio" name="dukungan_bpjs" value="1" {{ ($faskes?->dukungan_bpjs ?? false) ? 'checked' : '' }}> Ya, Menerima BPJS
                        </label>
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px;">
                            <input type="radio" name="dukungan_bpjs" value="0" {{ !($faskes?->dukungan_bpjs ?? false) ? 'checked' : '' }}> Tidak Menerima
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="wm-btn orange" style="width:100%;">
                        <i class="fas fa-save"></i> Simpan Perubahan Profil & Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Keamanan Akses -->
    <div class="wm-card mt-4">
        <div class="wm-card-header">
            <div class="wm-card-title"><i class="fas fa-lock" style="color:var(--orange);"></i> Ganti Password Akses</div>
        </div>
        <div class="wm-card-body">
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div style="display:grid; grid-template-columns: 1fr; gap: 18px;">
                    <div class="wm-form-group">
                        <label class="wm-label">Password Saat Ini <span style="color:#e74a3b">*</span></label>
                        <input type="password" name="current_password" class="wm-input" placeholder="Masukkan password saat ini..." required>
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label">Password Baru <span style="color:#e74a3b">*</span></label>
                        <input type="password" name="new_password" class="wm-input" placeholder="Minimal 8 karakter rahasia..." required minlength="8">
                    </div>
                    <div class="wm-form-group">
                        <label class="wm-label">Konfirmasi Password Baru <span style="color:#e74a3b">*</span></label>
                        <input type="password" name="new_password_confirmation" class="wm-input" placeholder="Ketik ulang password baru..." required minlength="8">
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <button type="submit" class="wm-btn orange" style="width:100%;">
                        <i class="fas fa-key"></i> Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== SECTION CHAT ADMIN ===== --}}
@include('partials.chat_mitra')

@endsection

</div>{{-- /faskesApp --}}

@push('scripts')
<script src="{{ asset('js/dashboard-faskes.js') }}"></script>
<script src="{{ asset('js/chat-mitra.js') }}"></script>
@endpush

