<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Faskes;
use App\Models\Mitra;
use App\Models\LaporanMasalah;
use App\Models\PendaftaranPariwisata;
use App\Models\UlasanFaskes;
use App\Models\RiwayatKunjungan;
use App\Models\JadwalDokter;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class DashboardRenderTest extends TestCase
{
    /**
     * Test that the admin dashboard view renders successfully without DB.
     */
    public function test_admin_dashboard_renders_successfully(): void
    {
        $dummyUser = (new User())->forceFill([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'gol_darah' => 'O',
            'kontak_darurat' => '0812345678',
            'is_active' => true,
        ]);

        $dummyFaskes = (new Faskes())->forceFill([
            'id' => 1,
            'nama_faskes' => 'Klinik Sehat',
            'jenis_faskes' => 'Klinik',
            'alamat' => 'Subang',
            'latitude' => -6.5718,
            'longitude' => 107.7600,
            'status_operasional' => 'open',
            'dukungan_bpjs' => true,
        ]);

        $dummyMitra = (new Mitra())->forceFill([
            'id' => 1,
            'nama_penanggung_jawab' => 'Dr. Smith',
            'email' => 'smith@example.com',
            'no_telp' => '081223344',
            'catatan_admin' => null,
            'created_at' => Carbon::now(),
        ]);
        $dummyMitra->setRelation('faskes', $dummyFaskes);

        $dummyWisata = (new PendaftaranPariwisata())->forceFill([
            'id' => 1,
            'nama_pengelola' => 'Budi',
            'nama_wisata' => 'Sari Ater',
            'kategori' => 'Pemandian Air Panas',
            'latitude' => -6.5718,
            'longitude' => 107.7600,
            'email_kontak' => 'sariater@example.com',
            'no_telp' => '08222333',
            'foto_path' => null,
            'created_at' => Carbon::now(),
            'alamat' => 'Subang',
        ]);

        $dummyLaporan = (new LaporanMasalah())->forceFill([
            'id' => 1,
            'deskripsi' => 'Lampu mati',
            'status' => 'pending',
            'created_at' => Carbon::now(),
        ]);
        $dummyLaporan->setRelation('user', $dummyUser);
        $dummyLaporan->setRelation('faskes', $dummyFaskes);

        $dummyUlasan = (new UlasanFaskes())->forceFill([
            'id' => 1,
            'rating' => 5,
            'komentar' => 'Sangat bagus',
            'created_at' => Carbon::now(),
        ]);
        $dummyUlasan->setRelation('user', $dummyUser);
        $dummyUlasan->setRelation('faskes', $dummyFaskes);

        $usersPaginator = new LengthAwarePaginator([$dummyUser], 1, 10);
        $faskesPaginator = new LengthAwarePaginator([$dummyFaskes], 1, 10);

        $viewData = [
            'totalWisatawan'  => 1,
            'totalFaskes'     => 1,
            'totalPariwisata' => 1,
            'pendingMitra'    => 2,
            'mitraPending'    => collect([$dummyMitra]),
            'wisataPending'   => collect([$dummyWisata]),
            'laporans'        => collect([$dummyLaporan]),
            'users'           => $usersPaginator,
            'faskesList'      => $faskesPaginator,
            'wisataApproved'  => collect([$dummyWisata]),
            'allUlasan'       => collect([$dummyUlasan]),
            'deletionRequests'=> collect([]),
        ];

        $html = view('dashboard.admin', $viewData)->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString('Helicopter View Sistem', $html);
        $this->assertStringContainsString('Pusat <span style="color:#ff7a00;">Kendali Utama</span>', $html);
    }

    /**
     * Test that the faskes dashboard view renders successfully without DB.
     */
    public function test_faskes_dashboard_renders_successfully(): void
    {
        $dummyUser = (new User())->forceFill([
            'name' => 'John Doe',
            'riwayat_alergi' => 'Udang',
            'riwayat_penyakit' => 'Asma',
            'gol_darah' => 'O',
        ]);

        $dummyFaskes = (new Faskes())->forceFill([
            'id' => 1,
            'nama_faskes' => 'Klinik Pratama',
            'jenis_faskes' => 'Klinik',
            'alamat' => 'Subang Raya',
            'no_telp' => '0260-12345',
            'latitude' => -6.5,
            'longitude' => 107.7,
            'status_operasional' => 'open',
            'is_24_jam' => true,
            'jam_buka' => '08:00',
            'jam_tutup' => '20:00',
            'dukungan_bpjs' => true,
            'pengumuman' => 'Halo semuanya',
            'pesan_admin' => 'Pesan dari admin',
            'layanan_tersedia' => ['Apotek', 'Ambulans'],
        ]);

        $dummyUlasan = (new UlasanFaskes())->forceFill([
            'rating' => 4,
            'komentar' => 'Pelayanan ramah',
            'created_at' => Carbon::now(),
        ]);
        $dummyUlasan->setRelation('user', $dummyUser);

        $dummyJadwal = (new JadwalDokter())->forceFill([
            'id' => 1,
            'nama_dokter' => 'Dr. Grace',
            'spesialisasi' => 'Poli Anak',
            'hari' => ['Senin', 'Selasa'],
            'jam_mulai' => '09:00',
            'jam_selesai' => '14:00',
        ]);

        $viewData = [
            'totalPengunjung' => 5,
            'totalUlasan'     => 1,
            'faskes'          => $dummyFaskes,
            'ulasans'         => collect([$dummyUlasan]),
            'jadwals'         => collect([$dummyJadwal]),
        ];

        $html = view('dashboard.faskes', $viewData)->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString('Panel Kontrol Faskes', $html);
    }

    /**
     * Test that the wisatawan dashboard view renders successfully without DB.
     */
    public function test_wisatawan_dashboard_renders_successfully(): void
    {
        $dummyUser = (new User())->forceFill([
            'id' => 1,
            'name' => 'Alice Cooper',
            'email' => 'alice@example.com',
            'created_at' => Carbon::now(),
            'gol_darah' => 'AB',
            'kontak_darurat' => '0899999999',
            'riwayat_alergi' => null,
            'riwayat_penyakit' => null,
            'recovery_pin' => '123456',
        ]);

        $dummyFaskes = (new Faskes())->forceFill([
            'nama_faskes' => 'Klinik Citra',
        ]);

        $dummyRiwayat = (new RiwayatKunjungan())->forceFill([
            'label_warna' => 'green',
            'tanggal_kunjungan' => Carbon::now(),
            'catatan_pribadi' => 'Dokter sangat baik',
        ]);
        $dummyRiwayat->setRelation('faskes', $dummyFaskes);

        $viewData = [
            'user'             => $dummyUser,
            'riwayats'         => collect([$dummyRiwayat]),
            'totalKunjungan'   => 1,
            'kunjunganBulan'   => 1,
            'rekomendasiCount' => 1,
        ];

        $html = view('dashboard.wisatawan', $viewData)->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString('Halo, Alice!', $html);
        $this->assertStringContainsString('Portal Wisatawan', $html);
    }

    /**
     * Test that the faskes dashboard renders successfully when faskes and/or mitra is null.
     */
    public function test_faskes_dashboard_renders_with_null_profile_successfully(): void
    {
        $viewData = [
            'totalPengunjung' => 0,
            'totalUlasan'     => 0,
            'faskes'          => null,
            'mitra'           => null,
            'ulasans'         => collect([]),
            'jadwals'         => collect([]),
        ];

        $html = view('dashboard.faskes', $viewData)->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString('Panel Kontrol Faskes', $html);
        $this->assertStringContainsString('Mitra Faskes', $html); // fallback user_name
    }

    /**
     * Test that the wisatawan layout is robust even if user is completely null.
     */
    public function test_wisatawan_layout_renders_with_null_user_successfully(): void
    {
        $viewData = [
            'user'             => null,
            'riwayats'         => collect([]),
            'totalKunjungan'   => 0,
            'kunjunganBulan'   => 0,
            'rekomendasiCount' => 0,
        ];

        $html = view('layouts.wisatawan.main', $viewData)->render();

        $this->assertNotEmpty($html);
        $this->assertStringContainsString('Wisatawan', $html); // fallback name in sidebar/profile strip
    }
}
