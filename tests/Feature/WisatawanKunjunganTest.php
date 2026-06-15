<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Faskes;
use App\Models\Mitra;
use App\Models\RiwayatKunjungan;

class WisatawanKunjunganTest extends TestCase
{
    use RefreshDatabase;

    public function test_wisatawan_can_record_health_facility_visit(): void
    {
        $user = User::factory()->create();

        $mitra = Mitra::create([
            'nama_penanggung_jawab' => 'Mitra Test',
            'email' => 'mitra@test.com',
            'password' => bcrypt('password'),
            'no_telp' => '0812345678',
            'jenis_mitra' => 'faskes',
        ]);

        $faskes = Faskes::create([
            'mitra_id' => $mitra->id,
            'nama_faskes' => 'Klinik Sukamaju',
            'jenis_faskes' => 'Klinik',
            'alamat' => 'Subang',
            'latitude' => -6.5718,
            'longitude' => 107.7600,
            'status_operasional' => 'open',
            'dukungan_bpjs' => true,
        ]);

        $response = $this->withSession([
            'auth_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'wisatawan',
            ]
        ])->postJson(route('wisatawan.kunjungan.store'), [
            'faskes_id' => $faskes->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kunjungan berhasil dicatat!',
        ]);

        $this->assertDatabaseHas('riwayat_kunjungan', [
            'user_id' => $user->id,
            'faskes_id' => $faskes->id,
            'label_warna' => 'yellow',
        ]);
    }

    public function test_cannot_record_duplicate_visit_on_the_same_day(): void
    {
        $user = User::factory()->create();
        
        $mitra = Mitra::create([
            'nama_penanggung_jawab' => 'Mitra Test',
            'email' => 'mitra@test.com',
            'password' => bcrypt('password'),
            'no_telp' => '0812345678',
            'jenis_mitra' => 'faskes',
        ]);

        $faskes = Faskes::create([
            'mitra_id' => $mitra->id,
            'nama_faskes' => 'Klinik Sukamaju',
            'jenis_faskes' => 'Klinik',
            'alamat' => 'Subang',
            'latitude' => -6.5718,
            'longitude' => 107.7600,
            'status_operasional' => 'open',
            'dukungan_bpjs' => true,
        ]);

        // Record first visit
        RiwayatKunjungan::create([
            'user_id' => $user->id,
            'faskes_id' => $faskes->id,
            'tanggal_kunjungan' => today(),
            'label_warna' => 'yellow',
        ]);

        // Send request to record again
        $response = $this->withSession([
            'auth_user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'wisatawan',
            ]
        ])->postJson(route('wisatawan.kunjungan.store'), [
            'faskes_id' => $faskes->id,
        ]);

        $response->assertStatus(200);
        
        // Count should still be 1
        $this->assertEquals(1, RiwayatKunjungan::count());
    }
}
