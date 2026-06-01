@extends('layouts.wisatawan.main')

@section('content')

        @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- Welcome Banner --}}
        <div class="welcome-banner">
            <div class="wb-title">Halo, {{ explode(' ', trim($user->name))[0] }}! 👋</div>
            <div class="wb-desc">Selamat datang di portal wisatawan WanderMed. Kelola kesehatan dan temukan fasilitas medis terbaik di Subang.</div>
            <div class="wb-actions">
                <a href="/peta-faskes" class="wb-btn-solid wb-btn">
                    <i class="fas fa-map-marked-alt"></i> Peta Faskes
                </a>
                <button class="wb-btn" onclick="switchTab('medis')">
                    <i class="fas fa-notes-medical"></i> Rekam Medis
                </button>
            </div>
        </div>



        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="sc-icon blue"><i class="fas fa-hospital-user"></i></div>
                <div class="sc-body">
                    <div class="sc-val">{{ $totalKunjungan }}</div>
                    <div class="sc-lbl">Total Kunjungan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="sc-icon orange"><i class="fas fa-calendar-check"></i></div>
                <div class="sc-body">
                    <div class="sc-val">{{ $kunjunganBulan }}</div>
                    <div class="sc-lbl">Bulan Ini</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="sc-icon green"><i class="fas fa-star"></i></div>
                <div class="sc-body">
                    <div class="sc-val">{{ $rekomendasiCount }}</div>
                    <div class="sc-lbl">Direkomendasikan</div>
                </div>
            </div>
        </div>

        {{-- Tab Pills --}}
        <div class="w-pills">
            <button class="w-pill active" id="pill-riwayat" data-target="tab-riwayat" onclick="switchTab('riwayat')">
                <i class="fas fa-history"></i> Riwayat
            </button>
            <button class="w-pill" id="pill-profil" data-target="tab-profil" onclick="switchTab('profil')">
                <i class="fas fa-user-cog"></i> Akun
            </button>
            <button class="w-pill" id="pill-medis" data-target="tab-medis" onclick="switchTab('medis')">
                <i class="fas fa-notes-medical"></i> Medis
            </button>
        </div>

        {{-- ── TAB: RIWAYAT KUNJUNGAN ── --}}
        <div id="tab-riwayat" class="w-pane active">
            <div class="w-card">
                <div class="w-card-header">
                    <i class="fas fa-history"></i> Riwayat Kunjungan Faskes
                </div>
                <div class="w-card-body">
                    @if($riwayats->count() > 0)
                    <div class="history-list">
                        @foreach($riwayats as $r)
                        @php
                            $lbl = $r->label_warna ?? 'yellow';
                            $ico = ['green'=>'fa-star','yellow'=>'fa-check','red'=>'fa-times-circle'];
                            $txt = ['green'=>'Direkomendasikan','yellow'=>'Cukup Baik','red'=>'Tidak Disarankan'];
                            $cls = ['green'=>'g','yellow'=>'y','red'=>'r'];
                        @endphp
                        <div class="h-item">
                            <div class="h-icon {{ $cls[$lbl] ?? 'y' }}">
                                <i class="fas {{ $ico[$lbl] ?? 'fa-check' }}"></i>
                            </div>
                            <div class="h-main">
                                <div class="h-name">{{ $r->faskes ? $r->faskes->nama_faskes : 'Faskes Tidak Diketahui' }}</div>
                                <div class="h-meta">
                                    <span><i class="fas fa-calendar-alt"></i> {{ $r->tanggal_kunjungan->format('d M Y') }}</span>
                                </div>
                                @if($r->catatan_pribadi)
                                <div class="h-note">"{{ $r->catatan_pribadi }}"</div>
                                @endif
                            </div>
                            <div class="h-badge {{ $cls[$lbl] ?? 'y' }}">{{ $txt[$lbl] ?? '' }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="h-empty">
                        <i class="fas fa-folder-open"></i>
                        <p>Belum ada riwayat kunjungan yang dicatat.</p>
                        <a href="/peta-faskes" class="w-btn w-btn-orange" style="padding:12px 24px; font-size:14px;">
                            <i class="fas fa-map-marked-alt"></i> Jelajahi Peta Faskes
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── TAB: PENGATURAN AKUN ── --}}
        <div id="tab-profil" class="w-pane">
            <div class="w-card">
                <div class="w-card-header"><i class="fas fa-user-cog"></i> Pengaturan Akun</div>
                <div class="w-card-body">
                    <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                        @csrf
                        <div class="w-form-grid">
                            <div>
                                <label class="w-label">Nama Lengkap</label>
                                <input type="text" name="name" class="w-input" value="{{ old('name', $user->name) }}" required maxlength="100">
                            </div>
                            <div>
                                <label class="w-label">Email (Tidak dapat diubah)</label>
                                <input type="email" class="w-input" value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="w-btn w-btn-orange" style="padding:11px 28px;">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>

                    <hr style="border-color:var(--border); margin:24px 0;">

                    <h5 style="font-size:14px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-lock" style="color:var(--orange)"></i> Ganti Password
                    </h5>
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <div class="w-form-group">
                            <label class="w-label">Password Saat Ini</label>
                            <input type="password" name="current_password" class="w-input" placeholder="Password lama..." required>
                        </div>
                        <div class="w-form-grid">
                            <div>
                                <label class="w-label">Password Baru</label>
                                <input type="password" name="new_password" class="w-input" placeholder="Min. 8 karakter" required minlength="8">
                            </div>
                            <div>
                                <label class="w-label">Konfirmasi Password</label>
                                <input type="password" name="new_password_confirmation" class="w-input" placeholder="Ulangi password baru" required minlength="8">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="w-btn w-btn-orange" style="padding:11px 28px;">
                                <i class="fas fa-key"></i> Perbarui Password
                            </button>
                        </div>
                    </form>

                    <hr style="border-color:var(--border); margin:24px 0;">

                    <h5 style="font-size:14px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                        <i class="fab fa-telegram-plane" style="color:#0088cc"></i> Integrasi Bot Telegram
                    </h5>
                    <div style="background:rgba(0,136,204,0.05); border:1px solid rgba(0,136,204,0.2); border-radius:10px; padding:18px; margin-bottom: 24px;">
                        @if($user->telegram_chat_id)
                            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                                <div>
                                    <p style="font-size:13px; font-weight:600; color:var(--text-primary); margin-bottom:4px;">
                                        <i class="fas fa-check-circle" style="color:#28a745; margin-right:4px;"></i> Status: Terhubung dengan Telegram
                                    </p>
                                    <p style="font-size:12px; color:var(--text-secondary); margin-bottom:0;">
                                        ID Chat Telegram Anda: <code>{{ $user->telegram_chat_id }}</code>. Anda sekarang dapat menggunakan bot untuk reset sandi secara instan.
                                    </p>
                                </div>
                                <form action="{{ route('wisatawan.telegram.unlink') }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="w-btn" style="padding:10px 20px; background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3); font-size:13px; font-weight:600;">
                                        <i class="fas fa-unlink"></i> Putuskan Hubungan
                                    </button>
                                </form>
                            </div>
                        @else
                            <p style="font-size:12.5px; color:var(--text-secondary); margin-bottom:14px; line-height:1.5;">
                                Hubungkan akun Anda dengan bot Telegram kami untuk kemudahan pemulihan akun instan secara mandiri.
                            </p>
                            @if($user->telegram_verification_code)
                                <div style="background:rgba(0,0,0,0.1); border-left:4px solid #0088cc; padding:12px; border-radius:6px; margin-bottom:14px;">
                                    <p style="font-size:13px; font-weight:700; color:var(--text-primary); margin-bottom:6px;">Langkah Menghubungkan:</p>
                                    <ol style="font-size:12.5px; color:var(--text-secondary); padding-left:20px; margin-bottom:0; line-height:1.6;">
                                        <li>Buka bot Telegram kami di: <a href="https://t.me/{{ env('TELEGRAM_BOT_USERNAME', 'wandermed_recovery_bot') }}" target="_blank" style="color:#0088cc; font-weight:700;">@{{ env('TELEGRAM_BOT_USERNAME', 'wandermed_recovery_bot') }} <i class="fas fa-external-link-alt" style="font-size:10px;"></i></a></li>
                                        <li>Kirimkan perintah ini ke bot: <code style="color:#ff7a00; font-weight:bold; background:rgba(0,0,0,0.2); padding:2px 6px; border-radius:4px;">/start {{ $user->telegram_verification_code }}</code></li>
                                        <li>Akun Anda akan langsung terhubung secara otomatis!</li>
                                    </ol>
                                </div>
                            @endif
                            <form action="{{ route('wisatawan.telegram.generate') }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="w-btn w-btn-orange" style="padding:11px 28px; background:#0088cc; border-color:#0088cc;">
                                    <i class="fas fa-link"></i> {{ $user->telegram_verification_code ? 'Generate Kode Baru' : 'Hubungkan Telegram' }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <hr style="border-color:var(--border); margin:24px 0;">

                    <h5 style="font-size:14px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px; color:var(--red);">
                        <i class="fas fa-exclamation-triangle" style="color:var(--red)"></i> Hapus Akun (Danger Zone)
                    </h5>
                    <div style="background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.2); border-radius:10px; padding:18px;">
                        <p style="font-size:12.5px; color:var(--text-secondary); margin-bottom:14px; line-height:1.5;">
                            Jika Anda ingin menghapus akun wisatawan Anda secara permanen dari platform WanderMed, silakan ajukan permohonan penghapusan akun ke admin. Akun Anda beserta seluruh data rekam medis dan riwayat kunjungan akan dihapus secara permanen setelah permohonan disetujui.
                        </p>
                        <button type="button" class="w-btn" onclick="requestAccountDeletionFromDashboard('{{ $user->email }}', '{{ csrf_token() }}')" style="padding:11px 28px; background:rgba(239,68,68,0.15); color:var(--red); border:1px solid rgba(239,68,68,0.4); font-weight:600;">
                            <i class="fas fa-trash-alt"></i> Ajukan Penghapusan Akun
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TAB: REKAM MEDIS ── --}}
        <div id="tab-medis" class="w-pane">
            <div class="w-card" style="border-top: 3px solid var(--red);">
                <div class="w-card-header" style="color:var(--red);">
                    <i class="fas fa-notes-medical" style="color:var(--red)"></i> Rekam Medis Darurat Pribadi
                </div>
                <div class="w-card-body">
                    <div class="info-banner">
                        <i class="fas fa-info-circle"></i>
                        <div><strong>Perhatian:</strong> Data medis darurat ini membantu petugas medis memahami kondisi Anda jika terjadi kedaruratan saat berwisata di Subang.</div>
                    </div>

                    <form action="{{ route('wisatawan.profil.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <div class="w-form-grid">
                            <div>
                                <label class="w-label">Golongan Darah</label>
                                <select name="gol_darah" class="w-select">
                                    <option value="">– Belum Diketahui –</option>
                                    @foreach(['A','B','AB','O'] as $gd)
                                    <option value="{{ $gd }}" {{ $user->gol_darah == $gd ? 'selected' : '' }}>Golongan {{ $gd }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="w-label">Kontak Darurat</label>
                                <input type="text" name="kontak_darurat" class="w-input"
                                    value="{{ old('kontak_darurat', $user->kontak_darurat) }}"
                                    placeholder="Contoh: 08123456789 (Nama)" maxlength="15">
                            </div>
                        </div>
                        <div class="w-form-group">
                            <label class="w-label">Riwayat Alergi (Opsional)</label>
                            <textarea name="riwayat_alergi" class="w-textarea"
                                placeholder="Contoh: Alergi dingin, udang, parasetamol, dsb..." maxlength="200">{{ old('riwayat_alergi', $user->riwayat_alergi) }}</textarea>
                        </div>
                        <div class="w-form-group">
                            <label class="w-label">Riwayat Kesehatan / Penyakit Bawaan (Opsional)</label>
                            <textarea name="riwayat_penyakit" class="w-textarea"
                                placeholder="Contoh: Asma ringan, maag, dsb..." maxlength="200">{{ old('riwayat_penyakit', $user->riwayat_penyakit) }}</textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="w-btn" style="padding:11px 28px; background:rgba(239,68,68,0.1); color:var(--red); border:1px solid rgba(239,68,68,0.3);">
                                <i class="fas fa-shield-alt"></i> Simpan Data Medis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection
