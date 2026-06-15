<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\PendaftaranPariwisata;

class PartnerValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('=== SEEDER VALIDASI MITRA (PENDING & AUTO-APPROVED) ===');

        // Clean up old dummy data to prevent unique constraints violations
        $emails = [
            'andi.pending@example.com',
            'budi.pending@example.com',
            'citra.auto@example.com'
        ];
        $mitraIds = Mitra::whereIn('email', $emails)->pluck('id');
        Faskes::whereIn('mitra_id', $mitraIds)->delete();
        Mitra::whereIn('email', $emails)->delete();

        $wisataEmails = [
            'hendra.pending@example.com',
            'gilang.pending@example.com',
            'doni.auto@example.com'
        ];
        PendaftaranPariwisata::whereIn('email_kontak', $wisataEmails)->delete();

        $fourDaysAgo = now()->subDays(4);

        // 1. FASKES PENDING (Terdaftar 4 hari yang lalu - akan ter-ACC Otomatis saat memuat halaman/command)
        $mitra1 = Mitra::create([
            'nama_penanggung_jawab' => 'dr. Andi Wijaya (Pending >3 Hari)',
            'email' => 'andi.pending@example.com',
            'password' => Hash::make('password123'),
            'no_telp' => '081234567801',
            'jenis_mitra' => 'faskes',
            'is_verified' => false,
            'is_auto_approved' => false,
        ]);
        DB::table('mitras')->where('id', $mitra1->id)->update([
            'created_at' => $fourDaysAgo,
            'updated_at' => $fourDaysAgo
        ]);

        $mitra1->faskes()->create([
            'nama_faskes' => 'Klinik Wijaya Medika',
            'jenis_faskes' => 'Klinik',
            'alamat' => 'Jl. Pahlawan No. 10, Subang',
            'status_operasional' => 'closed',
            'latitude' => -6.5718,
            'longitude' => 107.7600,
            'no_telp' => '081234567801',
        ]);

        // 2. FASKES PENDING (Terdaftar hari ini - akan tetap Pending)
        $mitra2 = Mitra::create([
            'nama_penanggung_jawab' => 'dr. Budi Setiawan (Pending Baru)',
            'email' => 'budi.pending@example.com',
            'password' => Hash::make('password123'),
            'no_telp' => '081234567802',
            'jenis_mitra' => 'faskes',
            'is_verified' => false,
            'is_auto_approved' => false,
        ]);

        $mitra2->faskes()->create([
            'nama_faskes' => 'Klinik Budi Husada',
            'jenis_faskes' => 'Klinik',
            'alamat' => 'Jl. Merdeka No. 22, Subang',
            'status_operasional' => 'closed',
            'latitude' => -6.5745,
            'longitude' => 107.7625,
            'no_telp' => '081234567802',
        ]);

        // 3. PARIWISATA PENDING (Terdaftar 4 hari yang lalu - akan ter-ACC Otomatis saat memuat halaman/command)
        $wisata1 = PendaftaranPariwisata::create([
            'nama_wisata' => 'Curug Sawer Subang (Pending >3 Hari)',
            'kategori' => 'Alam',
            'alamat' => 'Desa Wisata Sagalaherang, Subang',
            'nama_pengelola' => 'Pak Hendra (Pending >3 Hari)',
            'email_kontak' => 'hendra.pending@example.com',
            'no_telp' => '089876543201',
            'status_review' => 'menunggu',
            'latitude' => -6.6500,
            'longitude' => 107.6300,
            'is_auto_approved' => false,
        ]);
        DB::table('pendaftaran_pariwisata')->where('id', $wisata1->id)->update([
            'created_at' => $fourDaysAgo,
            'updated_at' => $fourDaysAgo
        ]);

        // 4. PARIWISATA PENDING (Terdaftar hari ini - akan tetap Pending)
        PendaftaranPariwisata::create([
            'nama_wisata' => 'Bukit Bintang Subang (Pending Baru)',
            'kategori' => 'Alam',
            'alamat' => 'Kec. Ciater, Subang',
            'nama_pengelola' => 'Pak Gilang (Pending Baru)',
            'email_kontak' => 'gilang.pending@example.com',
            'no_telp' => '089876543202',
            'status_review' => 'menunggu',
            'latitude' => -6.7320,
            'longitude' => 107.6480,
            'is_auto_approved' => false,
        ]);

        // 5. FASKES AUTO-APPROVED (Sudah di-ACC Otomatis sebelumnya)
        $mitra3 = Mitra::create([
            'nama_penanggung_jawab' => 'dr. Citra Lestari (ACC Otomatis)',
            'email' => 'citra.auto@example.com',
            'password' => Hash::make('password123'),
            'no_telp' => '081234567803',
            'jenis_mitra' => 'faskes',
            'is_verified' => true,
            'is_auto_approved' => true,
        ]);
        DB::table('mitras')->where('id', $mitra3->id)->update([
            'created_at' => $fourDaysAgo,
            'updated_at' => $fourDaysAgo
        ]);

        $mitra3->faskes()->create([
            'nama_faskes' => 'Apotek Citra Sehat (ACC Otomatis)',
            'jenis_faskes' => 'Apotek',
            'alamat' => 'Jl. Gatot Subroto No. 5, Subang',
            'status_operasional' => 'open',
            'latitude' => -6.5685,
            'longitude' => 107.7565,
            'no_telp' => '081234567803',
        ]);

        // 6. PARIWISATA AUTO-APPROVED (Sudah di-ACC Otomatis sebelumnya)
        $wisata3 = PendaftaranPariwisata::create([
            'nama_wisata' => 'Taman Pinus Subang (ACC Otomatis)',
            'kategori' => 'Alam',
            'alamat' => 'Desa Palasari, Ciater, Subang',
            'nama_pengelola' => 'Pak Doni (ACC Otomatis)',
            'email_kontak' => 'doni.auto@example.com',
            'no_telp' => '089876543203',
            'status_review' => 'disetujui',
            'latitude' => -6.7280,
            'longitude' => 107.6790,
            'is_auto_approved' => true,
        ]);
        DB::table('pendaftaran_pariwisata')->where('id', $wisata3->id)->update([
            'created_at' => $fourDaysAgo,
            'updated_at' => $fourDaysAgo
        ]);

        $this->command->info('Seed selesai! Silakan buka halaman dashboard admin atau jalankan command `php artisan partners:auto-approve` untuk memicu auto-approval.');
    }
}
