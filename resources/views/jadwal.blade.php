@extends('layouts.public')
@section('title', 'Jadwal Praktik Dokter - ' . $faskes->nama_faskes)

@section('content')
    @include('partials.navbar')

    <section class="py-5" style="min-height: 80vh; padding-top: 120px !important;">
        <div class="container px-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    
                    <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="600">
                        <h2 class="teks-judul font-weight-bold mb-3 text-white">Jadwal Praktik Dokter</h2>
                        <div style="width: 60px; height: 3px; background-color: var(--hnb-orange); margin: 0 auto 20px auto; border-radius: 2px;"></div>
                        <p class="teks-subjudul mx-auto text-muted mb-0">Jadwal dokter terdaftar di fasilitas kesehatan ini.</p>
                    </div>

                    <div class="glass-premier radius-hnb p-4 p-md-5 mb-4 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-4" style="gap: 15px;">
                            <div>
                                <h3 class="font-weight-bold mb-2 faskes-title">{{ $faskes->nama_faskes }}</h3>
                                <p class="faskes-address mb-0"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $faskes->alamat }}</p>
                            </div>
                            @if($faskes->jadwals->isNotEmpty())
                                @php
                                    $lastUpdated = $faskes->jadwals->max('updated_at');
                                @endphp
                                @if($lastUpdated)
                                <div class="d-flex align-items-center">
                                    <div class="history-badge" style="font-size: 0.85rem; padding: 8px 14px; border-radius: 20px; font-weight: 500; white-space: nowrap;">
                                        <i class="fas fa-history mr-1" style="color: var(--hnb-orange);"></i> Diperbarui: {{ \Carbon\Carbon::parse($lastUpdated)->locale('id')->diffForHumans() }}
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>

                        @if($faskes->jadwals->isEmpty())
                            <div class="alert faskes-alert d-flex align-items-center" style="border-radius: 12px; padding: 20px;">
                                <i class="fas fa-info-circle fa-2x mr-3 text-info"></i> 
                                <div>Belum ada jadwal praktik dokter yang dipublikasikan oleh fasilitas kesehatan ini.</div>
                            </div>
                        @else
                            <div class="table-responsive" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                <table class="table table-hover mb-0 faskes-table">
                                    <thead class="faskes-table-thead">
                                        <tr>
                                            <th class="border-0 py-3 pl-4">Nama Dokter</th>
                                            <th class="border-0 py-3">Spesialisasi</th>
                                            <th class="border-0 py-3">Hari Praktik</th>
                                            <th class="border-0 py-3">Jam Praktik</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($faskes->jadwals as $jadwal)
                                        <tr>
                                            <td class="pl-4 py-3 align-middle font-weight-bold doctor-name">
                                                <i class="fas fa-user-md mr-2" style="color: #4e73df;"></i> {{ $jadwal->nama_dokter }}
                                            </td>
                                            <td class="py-3 align-middle">
                                                <span class="badge badge-pill spec-badge">
                                                    {{ $jadwal->spesialisasi }}
                                                </span>
                                            </td>
                                            <td class="py-3 align-middle">
                                                <i class="fas fa-calendar-day mr-1" style="color: #f6c23e;"></i> {{ is_array($jadwal->hari) ? implode(', ', $jadwal->hari) : $jadwal->hari }}
                                            </td>
                                            <td class="py-3 align-middle">
                                                <div class="time-block">
                                                    <i class="fas fa-clock mr-1" style="color: #858796;"></i> 
                                                    {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WIB
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                        <div class="mt-5 text-center">
                            <button onclick="window.history.back()" class="btn btn-back-map px-4 py-3">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Peta
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="bg-hnb-navy pt-5 pb-4 mt-5">
        @include('partials.footer')
    </section>
@endsection
