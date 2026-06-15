<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\PendaftaranPariwisata;

class CancelAutoApproveTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test cancel auto-approve for Faskes.
     */
    public function test_admin_can_cancel_auto_approve_faskes_and_preserve_data(): void
    {
        // 1. Create a Mitra and Faskes that was auto-approved
        $mitra = Mitra::create([
            'nama_penanggung_jawab' => 'Mitra Auto',
            'email' => 'mitra_auto@test.com',
            'password' => bcrypt('password'),
            'no_telp' => '0812345678',
            'jenis_mitra' => 'faskes',
            'is_verified' => true,
            'is_auto_approved' => true,
        ]);

        $faskes = Faskes::create([
            'mitra_id' => $mitra->id,
            'nama_faskes' => 'Klinik Auto Approved',
            'jenis_faskes' => 'Klinik',
            'alamat' => 'Subang Raya No. 45',
            'no_telp' => '0812345678',
            'latitude' => -6.57182230,
            'longitude' => 107.76001120,
            'status_operasional' => 'open',
            'dukungan_bpjs' => true,
            'nomor_izin_praktik' => 'IZIN-12345-AUTO',
            'foto_plang_izin_path' => 'dokumen_mitra/plang/sample.png',
            'foto_kondisi_faskes_path' => 'dokumen_mitra/kondisi/sample.png',
            'layanan_tersedia' => ['UGD 24 Jam', 'Apotek'],
            'pengumuman' => 'Pengumuman Faskes',
        ]);

        // 2. Submit cancel request as admin
        $response = $this->withSession([
            'auth_user' => [
                'id' => 0,
                'role' => 'admin',
                'email' => 'adminwandermed@gmail.com',
                'name' => 'Super Admin',
            ]
        ])->postJson("/admin/faskes/{$faskes->id}/cancel-auto-approve");

        // 3. Assert response is successful
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => "Faskes 'Klinik Auto Approved' berhasil dikembalikan ke antrean validasi."
        ]);

        // 4. Assert status has reverted
        $mitra->refresh();
        $faskes->refresh();

        $this->assertFalse($mitra->is_verified);
        $this->assertFalse($mitra->is_auto_approved);
        $this->assertTrue($mitra->is_auto_approve_cancelled);
        $this->assertEquals('closed', $faskes->status_operasional);

        // 5. Assert registration data is NOT lost
        $this->assertEquals('Klinik Auto Approved', $faskes->nama_faskes);
        $this->assertEquals('Subang Raya No. 45', $faskes->alamat);
        $this->assertEquals('0812345678', $faskes->no_telp);
        $this->assertEquals(-6.57182230, $faskes->latitude);
        $this->assertEquals(107.76001120, $faskes->longitude);
        $this->assertEquals('IZIN-12345-AUTO', $faskes->nomor_izin_praktik);
        $this->assertEquals('dokumen_mitra/plang/sample.png', $faskes->foto_plang_izin_path);
        $this->assertEquals('dokumen_mitra/kondisi/sample.png', $faskes->foto_kondisi_faskes_path);
        $this->assertEquals(['UGD 24 Jam', 'Apotek'], $faskes->layanan_tersedia);
        $this->assertEquals('Pengumuman Faskes', $faskes->pengumuman);

        // 6. Assert faskes is automatically NOT shown on the map
        $mapResponse = $this->get("/api/faskes");
        $mapResponse->assertStatus(200);
        $this->assertFalse(collect($mapResponse->json())->contains('id', $faskes->id));

        // 7. Assert faskes disappears from "ACC Otomatis" list but appears in "Validasi Mitra" queue
        $dashboardResponse = $this->withSession([
            'auth_user' => [
                'id' => 0,
                'role' => 'admin',
                'email' => 'adminwandermed@gmail.com',
                'name' => 'Super Admin',
            ]
        ])->get("/dashboard/admin");
        $dashboardResponse->assertStatus(200);

        $this->assertFalse($dashboardResponse->viewData('autoApprovedFaskes')->contains('id', $mitra->id));
        $this->assertTrue($dashboardResponse->viewData('mitraPending')->contains('id', $mitra->id));
    }

    /**
     * Test cancel auto-approve for Pariwisata.
     */
    public function test_admin_can_cancel_auto_approve_pariwisata_and_preserve_data(): void
    {
        // 1. Create a PendaftaranPariwisata that was auto-approved
        $wisata = PendaftaranPariwisata::create([
            'nama_pengelola' => 'Pengelola Pariwisata',
            'nama_wisata' => 'Wisata Auto Approved',
            'kategori' => 'Kawah Wisata',
            'latitude' => -6.55120000,
            'longitude' => 107.72140000,
            'email_kontak' => 'wisata_auto@test.com',
            'no_telp' => '0877777777',
            'alamat' => 'Jalan Wisata Kawah No. 12',
            'deskripsi' => 'Pemandangan kawah yang indah',
            'harga_tiket' => 25000,
            'foto_path' => 'pariwisata/sample.png',
            'status_review' => 'disetujui',
            'is_auto_approved' => true,
        ]);

        // 2. Submit cancel request as admin
        $response = $this->withSession([
            'auth_user' => [
                'id' => 0,
                'role' => 'admin',
                'email' => 'adminwandermed@gmail.com',
                'name' => 'Super Admin',
            ]
        ])->postJson("/admin/pariwisata/{$wisata->id}/cancel-auto-approve");

        // 3. Assert response is successful
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => "Destinasi wisata 'Wisata Auto Approved' berhasil dikembalikan ke antrean validasi."
        ]);

        // 4. Assert status has reverted
        $wisata->refresh();

        $this->assertEquals('menunggu', $wisata->status_review);
        $this->assertFalse($wisata->is_auto_approved);
        $this->assertTrue($wisata->is_auto_approve_cancelled);

        // 5. Assert registration data is NOT lost
        $this->assertEquals('Pengelola Pariwisata', $wisata->nama_pengelola);
        $this->assertEquals('Wisata Auto Approved', $wisata->nama_wisata);
        $this->assertEquals('Kawah Wisata', $wisata->kategori);
        $this->assertEquals(-6.55120000, $wisata->latitude);
        $this->assertEquals(107.72140000, $wisata->longitude);
        $this->assertEquals('wisata_auto@test.com', $wisata->email_kontak);
        $this->assertEquals('0877777777', $wisata->no_telp);
        $this->assertEquals('Jalan Wisata Kawah No. 12', $wisata->alamat);
        $this->assertEquals('Pemandangan kawah yang indah', $wisata->deskripsi);
        $this->assertEquals(25000, $wisata->harga_tiket);
        $this->assertEquals('pariwisata/sample.png', $wisata->foto_path);

        // 6. Assert pariwisata is automatically NOT shown on the map
        $mapResponse = $this->get("/api/pariwisata");
        $mapResponse->assertStatus(200);
        $this->assertFalse(collect($mapResponse->json())->contains('id', 'p_' . $wisata->id));

        // 7. Assert pariwisata disappears from "ACC Otomatis" list but appears in "Validasi Mitra" queue
        $dashboardResponse = $this->withSession([
            'auth_user' => [
                'id' => 0,
                'role' => 'admin',
                'email' => 'adminwandermed@gmail.com',
                'name' => 'Super Admin',
            ]
        ])->get("/dashboard/admin");
        $dashboardResponse->assertStatus(200);

        $this->assertFalse($dashboardResponse->viewData('autoApprovedPariwisata')->contains('id', $wisata->id));
        $this->assertTrue($dashboardResponse->viewData('wisataPending')->contains('id', $wisata->id));
    }
}
