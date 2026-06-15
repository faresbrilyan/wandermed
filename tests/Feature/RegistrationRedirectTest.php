<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\PendaftaranPariwisata;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RegistrationRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_faskes_registration_redirects_to_daftar_portal(): void
    {
        Storage::fake('public');

        $response = $this->post('/daftar/faskes', [
            'nama_penanggung_jawab' => 'dr. Test Redirect',
            'email' => 'redirect.faskes@gmail.com',
            'jenis_mitra' => 'faskes',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'no_telp' => '081234567890',
            'jenis_faskes' => 'Klinik',
            'nama_faskes' => 'Klinik Test Redirect',
            'alamat' => 'Subang Indah',
            'latitude' => -6.5718,
            'longitude' => 107.7600,
            'nomor_izin_praktik' => 'SIP-999-RED',
            'foto_plang_izin' => UploadedFile::fake()->create('plang.png', 100),
            'foto_kondisi_faskes' => UploadedFile::fake()->create('kondisi.png', 100),
            'dukungan_bpjs' => '1',
            'layanan_ugd' => '24 Jam',
            'pengumuman' => 'Layanan Kesehatan Umum',
        ]);

        $response->assertRedirect('/daftar');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('mitras', [
            'email' => 'redirect.faskes@gmail.com',
            'jenis_mitra' => 'faskes',
            'is_verified' => false,
        ]);
    }

    public function test_pariwisata_registration_redirects_to_daftar_portal(): void
    {
        Storage::fake('public');

        $response = $this->post('/daftar/pariwisata', [
            'nama_wisata' => 'Curug Redirect Test',
            'kategori' => 'Alam',
            'deskripsi' => 'Curug yang sangat indah',
            'alamat' => 'Sariater, Subang',
            'latitude' => -6.5512,
            'longitude' => 107.7214,
            'nama_pengelola' => 'Pak Asep',
            'email_kontak' => 'asep.redirect@test.com',
            'no_telp' => '087777777788',
            'harga_tiket' => 15000,
            'foto_path' => UploadedFile::fake()->create('wisata.png', 100),
        ]);

        $response->assertRedirect('/daftar');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pendaftaran_pariwisata', [
            'email_kontak' => 'asep.redirect@test.com',
            'nama_wisata' => 'Curug Redirect Test',
            'status_review' => 'menunggu',
        ]);
    }
}
