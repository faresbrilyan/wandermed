<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Faskes;
use App\Models\PendaftaranPariwisata;
use App\Models\RiwayatKunjungan;
use App\Models\LaporanMasalah;
use App\Models\UlasanFaskes;
use App\Models\JadwalDokter;
use App\Models\Message;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        $this->command->info('=== SISTEM SEEDER WANDERMED (SUBANG SPASIAL) ===');
        $this->command->info('Membersihkan data lama...');

        // 1. DATA WISATAWAN / USERS (Tabel: users)
        $this->command->info('Membuat data wisatawan dengan locale Indonesia (id_ID)...');
        
        // Buat 40 wisatawan random lokal
        $users = [];
        for ($i = 0; $i < 40; $i++) {
            $users[] = User::create([
                'name'              => $faker->name(),
                'email'             => $faker->unique()->safeEmail(),
                'password'          => Hash::make('password123'),
                'recovery_pin'      => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'gol_darah'         => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'riwayat_alergi'    => $faker->randomElement([
                    'Alergi cuaca dingin / ekstrim',
                    'Alergi makanan laut (seafood/udang)',
                    'Alergi obat antibiotik Amoxicillin',
                    'Alergi debu tebal',
                    'Alergi kacang-kacangan',
                    'Alergi telur ayam',
                    null
                ]),
                'riwayat_penyakit'  => $faker->randomElement([
                    'Asma ringan',
                    'Maag kronis',
                    'Hipertensi terkontrol',
                    'Diabetes tipe 2 ringan',
                    'Anemia defisiensi besi',
                    null,
                    null,
                    null,
                ]),
                'kontak_darurat'    => $faker->phoneNumber(),
            ]);
        }

        // 2. DATA FASILITAS KESEHATAN (HARDCODED 21 DATA RIL/REALISTIS SUBANG)
        $this->command->info('Membuat 21 Faskes dan Akun Mitra di Kabupaten Subang...');
        
        $faskesList = [
            [
                'nama' => 'RSUD Ciereng Subang',
                'jenis' => 'Rumah Sakit',
                'lat' => -6.581452,
                'lng' => 107.768432,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Brigjen Katamso No.37, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211',
                'telp' => '(0260) 411421',
                'layanan' => ['UGD 24 Jam', 'Ambulans', 'Rawat Inap', 'Dok. Spesialis', 'Laboratorium', 'Radiologi', 'Farmasi'],
                'pengumuman' => 'Instalasi Gawat Darurat (UGD) beroperasi 24 jam penuh untuk rujukan darurat kecelakaan.',
            ],
            [
                'nama' => 'Puskesmas Jalancagak',
                'jenis' => 'Puskesmas',
                'lat' => -6.678120,
                'lng' => 107.702340,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Jalancagak No.88, Kec. Jalancagak, Kabupaten Subang, Jawa Barat 41281',
                'telp' => '(0260) 470088',
                'layanan' => ['Poli Umum', 'Rawat Jalan', 'Ambulans', 'KIA', 'UGD 24 Jam'],
                'pengumuman' => 'Pelayanan imunisasi balita dibuka setiap Selasa & Kamis pukul 08.00-11.00 WIB.',
            ],
            [
                'nama' => 'Puskesmas Ciater',
                'jenis' => 'Puskesmas',
                'lat' => -6.740234,
                'lng' => 107.660124,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Ciater, Kec. Ciater, Kabupaten Subang, Jawa Barat 41281',
                'telp' => '(0260) 470001',
                'layanan' => ['UGD 24 Jam', 'Ambulans', 'Poli Umum', 'KIA', 'Apotek'],
                'pengumuman' => 'Siap melayani penanganan pertama medis darurat untuk wisatawan di area wisata pegunungan.',
            ],
            [
                'nama' => 'Puskesmas Pamanukan',
                'jenis' => 'Puskesmas',
                'lat' => -6.284213,
                'lng' => 107.810543,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Pamanukan No.21, Kec. Pamanukan, Kabupaten Subang, Jawa Barat 41254',
                'telp' => '(0260) 550012',
                'layanan' => ['UGD 24 Jam', 'Poli Anak', 'Poli Umum', 'Ambulans', 'Rawat Inap'],
                'pengumuman' => 'Rawat inap tingkat pertama beroperasi 24 jam untuk wilayah Pantura Subang.',
            ],
            [
                'nama' => 'Klinik Pratama Medika Jalancagak',
                'jenis' => 'Klinik',
                'lat' => -6.681200,
                'lng' => 107.705000,
                'bpjs' => false,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Jalancagak No.110, Kec. Jalancagak, Kabupaten Subang, Jawa Barat 41281',
                'telp' => '081299881100',
                'layanan' => ['Poli Umum', 'Apotek', 'Rawat Jalan', 'Khitan Center'],
                'pengumuman' => 'Menerima konsultasi dokter umum dan khitan laser modern.',
            ],
            [
                'nama' => 'Klinik Asy-Syifa Ciater',
                'jenis' => 'Klinik',
                'lat' => -6.728900,
                'lng' => 107.662500,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Palasari, Kec. Ciater, Kabupaten Subang, Jawa Barat 41281',
                'telp' => '082211993344',
                'layanan' => ['Poli Umum', 'UGD 24 Jam', 'Apotek', 'Ambulans'],
                'pengumuman' => 'Melayani dokter keluarga BPJS. Standby penanganan UGD untuk wisatawan lokal.',
            ],
            [
                'nama' => 'Apotek K-24 Subang',
                'jenis' => 'Apotek',
                'lat' => -6.565432,
                'lng' => 107.750987,
                'bpjs' => false,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Cibogo No.12, Kec. Subang, Kabupaten Subang, Jawa Barat 41213',
                'telp' => '(0260) 422111',
                'layanan' => ['Apotek', 'Konsultasi Apoteker', 'Cek Gula Darah'],
                'pengumuman' => 'Buka 24 jam nonstop setiap hari termasuk hari libur nasional.',
            ],
            [
                'nama' => 'Apotek Kimia Farma Letjan Suprapto',
                'jenis' => 'Apotek',
                'lat' => -6.568901,
                'lng' => 107.758432,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Letjan Suprapto No.42, Cigadung, Kec. Subang, Kabupaten Subang, Jawa Barat 41213',
                'telp' => '(0260) 412345',
                'layanan' => ['Apotek', 'Konsultasi Apoteker', 'Tes Antigen', 'Klinik Pratama'],
                'pengumuman' => 'Melayani tebus resep obat BPJS kronis (Program Rujuk Balik / PRB).',
            ],
            [
                'nama' => 'Klinik Bakti Medika Kalijati',
                'jenis' => 'Klinik',
                'lat' => -6.532000,
                'lng' => 107.675000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Kalijati Barat No.45, Kec. Kalijati, Kabupaten Subang, Jawa Barat 41261',
                'telp' => '089911223344',
                'layanan' => ['Poli Umum', 'Poli Gigi', 'Apotek', 'Rawat Jalan'],
                'pengumuman' => 'Poli Gigi buka setiap hari Senin s.d Sabtu mulai pukul 15.00 WIB.',
            ],
            [
                'nama' => 'Puskesmas Kalijati',
                'jenis' => 'Puskesmas',
                'lat' => -6.529000,
                'lng' => 107.671000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Kalijati Timur No.10, Kec. Kalijati, Kabupaten Subang, Jawa Barat 41261',
                'telp' => '(0260) 460112',
                'layanan' => ['Poli Umum', 'KIA', 'Imunisasi', 'Ambulans', 'Apotek'],
                'pengumuman' => 'Pelayanan kesehatan tingkat pertama dasar bagi masyarakat Kalijati.',
            ],
            [
                'nama' => 'Puskesmas Pagaden',
                'jenis' => 'Puskesmas',
                'lat' => -6.480000,
                'lng' => 107.790000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Pagaden No.15, Kec. Pagaden, Kabupaten Subang, Jawa Barat 41252',
                'telp' => '(0260) 450099',
                'layanan' => ['Poli Umum', 'KIA', 'Ambulans', 'Apotek'],
                'pengumuman' => 'Rujukan berjenjang terintegrasi langsung dengan RSUD Ciereng Subang.',
            ],
            [
                'nama' => 'Klinik Utama Karanganyar',
                'jenis' => 'Klinik',
                'lat' => -6.575000,
                'lng' => 107.765000,
                'bpjs' => false,
                'operasional' => 'open',
                'alamat' => 'Jl. Otista No. 120, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211',
                'telp' => '081233445588',
                'layanan' => ['Poli Umum', 'Poli Gigi', 'Laboratorium', 'Rontgen', 'Dok. Spesialis'],
                'pengumuman' => 'Praktik Dokter Spesialis Penyakit Dalam buka jam 17.00 - 20.00 WIB.',
            ],
            [
                'nama' => 'Puskesmas Tanjungsiang',
                'jenis' => 'Puskesmas',
                'lat' => -6.745000,
                'lng' => 107.830000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Tanjungsiang No.4, Kec. Tanjungsiang, Kabupaten Subang, Jawa Barat 41284',
                'telp' => '(0260) 480110',
                'layanan' => ['UGD 24 Jam', 'Poli Umum', 'KIA', 'Ambulans', 'Apotek'],
                'pengumuman' => 'Unit Gawat Darurat (UGD) siaga 24 jam bagi masyarakat Subang wilayah selatan.',
            ],
            [
                'nama' => 'Klinik Pratama Cisalak',
                'jenis' => 'Klinik',
                'lat' => -6.715000,
                'lng' => 107.795000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Cisalak No.50, Kec. Cisalak, Kabupaten Subang, Jawa Barat 41283',
                'telp' => '087788990011',
                'layanan' => ['Poli Umum', 'Apotek', 'Rawat Jalan', 'Bidan Jaga'],
                'pengumuman' => 'Melayani pemeriksaan umum, KIA, KB, serta tebus obat murah.',
            ],
            [
                'nama' => 'Puskesmas Purwadadi',
                'jenis' => 'Puskesmas',
                'lat' => -6.450000,
                'lng' => 107.690000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Purwadadi No.82, Kec. Purwadadi, Kabupaten Subang, Jawa Barat 41261',
                'telp' => '(0260) 460012',
                'layanan' => ['Poli Umum', 'KIA', 'Imunisasi', 'Ambulans', 'Apotek'],
                'pengumuman' => 'Mengutamakan pelayanan promotif dan preventif bagi warga Purwadadi.',
            ],
            [
                'nama' => 'Puskesmas Ciasem',
                'jenis' => 'Puskesmas',
                'lat' => -6.330000,
                'lng' => 107.710000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Ciasem No.105, Kec. Ciasem, Kabupaten Subang, Jawa Barat 41256',
                'telp' => '(0260) 520110',
                'layanan' => ['UGD 24 Jam', 'Ambulans', 'Poli Umum', 'KIA', 'Apotek'],
                'pengumuman' => 'Siaga 24 jam untuk pertolongan medis jalur utama Pantura Subang.',
            ],
            [
                'nama' => 'Klinik Medika Ciasem',
                'jenis' => 'Klinik',
                'lat' => -6.332000,
                'lng' => 107.715000,
                'bpjs' => false,
                'operasional' => 'open',
                'alamat' => 'Jl. Jenderal Sudirman No.12, Kec. Ciasem, Kabupaten Subang, Jawa Barat 41256',
                'telp' => '081234455667',
                'layanan' => ['Poli Umum', 'Apotek', 'Rawat Jalan', 'Poli KIA'],
                'pengumuman' => 'Menyediakan fasilitas pemeriksaan dokter umum dan rawat luka.',
            ],
            [
                'nama' => 'Apotek Century Pamanukan',
                'jenis' => 'Apotek',
                'lat' => -6.281000,
                'lng' => 107.812000,
                'bpjs' => false,
                'operasional' => 'open',
                'alamat' => 'Jl. H. Ikhsan No.5, Kec. Pamanukan, Kabupaten Subang, Jawa Barat 41254',
                'telp' => '(0260) 550099',
                'layanan' => ['Apotek', 'Konsultasi Obat', 'Cek Tensi Darah'],
                'pengumuman' => 'Sedia obat paten terlengkap dengan konsultasi langsung apoteker berlisensi.',
            ],
            [
                'nama' => 'Puskesmas Binong',
                'jenis' => 'Puskesmas',
                'lat' => -6.420000,
                'lng' => 107.780000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Binong No.99, Kec. Binong, Kabupaten Subang, Jawa Barat 41252',
                'telp' => '(0260) 450112',
                'layanan' => ['Poli Umum', 'KIA', 'Imunisasi', 'Ambulans', 'Apotek'],
                'pengumuman' => 'Melayani dengan hati untuk mewujudkan masyarakat Binong yang sehat.',
            ],
            [
                'nama' => 'Puskesmas Sagalaherang',
                'jenis' => 'Puskesmas',
                'lat' => -6.650000,
                'lng' => 107.630000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Raya Sagalaherang No.15, Kec. Sagalaherang, Kabupaten Subang, Jawa Barat 41282',
                'telp' => '(0260) 470120',
                'layanan' => ['UGD 24 Jam', 'Ambulans', 'Poli Umum', 'KIA', 'Rawat Inap'],
                'pengumuman' => 'Puskesmas Dengan Tempat Perawatan (DTP) siaga 24 jam di jalur wisata Sagalaherang.',
            ],
            [
                'nama' => 'Klinik Bersalin Bunda Kasih Sagalaherang',
                'jenis' => 'Klinik',
                'lat' => -6.652000,
                'lng' => 107.633000,
                'bpjs' => true,
                'operasional' => 'open',
                'alamat' => 'Jl. Siliwangi No.8, Kec. Sagalaherang, Kabupaten Subang, Jawa Barat 41282',
                'telp' => '081399002211',
                'layanan' => ['Poli Bersalin', 'KIA', 'Dokter Jaga 24 Jam', 'Apotek', 'Ambulans'],
                'pengumuman' => 'Klinik bersalin amanah, melayani persalinan normal 24 jam penuh.',
            ],
        ];

        $insertedFaskes = [];
        $mitraList = [];

        foreach ($faskesList as $index => $item) {
            // Buat Akun Mitra (Tambahkan index agar dijamin unik)
            $email = strtolower(str_replace([' ', '-', '.'], '', $item['nama'])) . '_' . $index . '@gmail.com';
            $mitra = Mitra::create([
                'nama_penanggung_jawab' => $faker->name(),
                'email'                 => $email,
                'password'              => Hash::make('mitra123'),
                'recovery_pin'          => str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'no_telp'               => $faker->phoneNumber(),
                'jenis_mitra'           => 'faskes',
                'is_verified'           => true,
                'is_active'             => true,
            ]);

            $mitraList[] = $mitra;

            // Tentukan jam buka berdasarkan jenis faskes atau layanan
            $is24Jam = in_array('UGD 24 Jam', $item['layanan']) || in_array('Dokter Jaga 24 Jam', $item['layanan']) || str_contains(strtolower($item['pengumuman']), 'buka 24 jam');
            $jamBuka = $is24Jam ? '00:00:00' : '08:00:00';
            $jamTutup = $is24Jam ? '23:59:59' : '20:00:00';

            // Buat Profil Faskes
            $insertedFaskes[] = Faskes::create([
                'mitra_id'           => $mitra->id,
                'nama_faskes'        => $item['nama'],
                'jenis_faskes'       => $item['jenis'],
                'latitude'           => $item['lat'],
                'longitude'          => $item['lng'],
                'status_operasional' => $item['operasional'],
                'jam_buka'           => $jamBuka,
                'jam_tutup'          => $jamTutup,
                'is_24_jam'          => $is24Jam,
                'dukungan_bpjs'      => $item['bpjs'],
                'alamat'             => $item['alamat'],
                'no_telp'            => $item['telp'],
                'pengumuman'         => $item['pengumuman'],
                'layanan_tersedia'   => $item['layanan'],
            ]);
        }


        // 3. DATA MITRA PARIWISATA (21 DESTINASI WISATA NYATA DI SUBANG)
        $this->command->info('Membuat 21 Destinasi Wisata di Kabupaten Subang...');

        $wisataList = [
            [
                'nama' => 'Pemandian Air Panas Sari Ater',
                'kategori' => 'Alam',
                'deskripsi' => 'Kolam pemandian air panas alami bersuhu sejuk pegunungan yang bersumber langsung dari kawah aktif Gunung Tangkuban Perahu.',
                'alamat' => 'Jl. Raya Ciater, Kecamatan Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.736342,
                'lng' => 107.653432,
                'pengelola' => 'Bapak Asep Sumarna',
                'email' => 'asepsariater@gmail.com',
                'telp' => '081199887766',
                'tiket' => 35000,
            ],
            [
                'nama' => 'Kawah Tangkuban Perahu',
                'kategori' => 'Alam',
                'deskripsi' => 'Gunung berapi aktif legendaris dengan pemandangan Kawah Ratu yang memukau dan legenda Sangkuriang yang terkenal.',
                'alamat' => 'Desa Cikahuripan, Kecamatan Lembang / Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.759632,
                'lng' => 107.609654,
                'pengelola' => 'Ibu Lilis Hartini',
                'email' => 'lilistangkuban@gmail.com',
                'telp' => '082299887766',
                'tiket' => 20000,
            ],
            [
                'nama' => 'Florawisata D Castello',
                'kategori' => 'Buatan',
                'deskripsi' => 'Taman bunga terhampar luas yang dihiasi kastil megah berwarna-warni perpaduan gaya arsitektur Rusia dan Turki.',
                'alamat' => 'Jl. Raya Ciater, Kecamatan Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.712345,
                'lng' => 107.671234,
                'pengelola' => 'Pak Dedi Mulyadi',
                'email' => 'dedidcastello@gmail.com',
                'telp' => '081377889900',
                'tiket' => 30000,
            ],
            [
                'nama' => 'Desa Wisata Cibeusi & Curug Cibareubeu',
                'kategori' => 'Alam',
                'deskripsi' => 'Keasrian pedesaan asri dengan hamparan sawah terasering berundak serta air terjun Curug Cibareubeu yang eksotis.',
                'alamat' => 'Desa Cibeusi, Kecamatan Jalancagak, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.698765,
                'lng' => 107.698765,
                'pengelola' => 'Pak Nanang Kurnia',
                'email' => 'cibeusiwisata@gmail.com',
                'telp' => '085288991122',
                'tiket' => 10000,
            ],
            [
                'nama' => 'Desa Wisata Cisaat',
                'kategori' => 'Budaya',
                'deskripsi' => 'Desa wisata edukatif yang menyuguhkan edukasi peternakan sapi perah, kebun teh, dan kearifan budaya Sunda lokal.',
                'alamat' => 'Desa Cisaat, Kecamatan Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.711000,
                'lng' => 107.641000,
                'pengelola' => 'Pak Enjang Setiawan',
                'email' => 'cisaatedukasi@gmail.com',
                'telp' => '085344556677',
                'tiket' => 15000,
            ],
            [
                'nama' => 'Curug Cijalu',
                'kategori' => 'Alam',
                'deskripsi' => 'Pancuran air terjun mempesona di lereng Gunung Burangrang yang dikelilingi rindang pohon pinus bernuansa mistis alami.',
                'alamat' => 'Desa Cipancar, Kecamatan Sagalaherang, Kabupaten Subang, Jawa Barat 41282',
                'lat' => -6.685000,
                'lng' => 107.602000,
                'pengelola' => 'Bapak Wawan Ridwan',
                'email' => 'curugcijalu@gmail.com',
                'telp' => '087811223344',
                'tiket' => 17500,
            ],
            [
                'nama' => 'Curug Cileat Cisalak',
                'kategori' => 'Petualangan',
                'deskripsi' => 'Air terjun tertinggi di Kabupaten Subang yang memerlukan trekking menembus hutan rimba yang menantang dan asri.',
                'alamat' => 'Desa Mayang, Kecamatan Cisalak, Kabupaten Subang, Jawa Barat 41283',
                'lat' => -6.719876,
                'lng' => 107.798765,
                'pengelola' => 'Bapak Herman',
                'email' => 'curugcileat@gmail.com',
                'telp' => '087711223344',
                'tiket' => 10000,
            ],
            [
                'nama' => 'Asstro Highland Ciater',
                'kategori' => 'Petualangan',
                'deskripsi' => 'Destinasi kebun teh kekinian dengan fasilitas glamping premium, kafe estetik, dan panorama alam pegunungan berkabut.',
                'alamat' => 'Jl. Raya Subang-Bandung Km.12, Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.751000,
                'lng' => 107.625000,
                'pengelola' => 'Ibu Rina Astuti',
                'email' => 'asstrohighland@gmail.com',
                'telp' => '081223344556',
                'tiket' => 25000,
            ],
            [
                'nama' => 'Kebun Teh Ciater',
                'kategori' => 'Alam',
                'deskripsi' => 'Hamparan perkebunan teh bergelombang hijau yang luas berlatar Gunung Tangkuban Perahu dengan suhu udara sejuk dingin.',
                'alamat' => 'Kecamatan Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.732000,
                'lng' => 107.648000,
                'pengelola' => 'Pak Diding',
                'email' => 'kebuntehciater@gmail.com',
                'telp' => '089877665544',
                'tiket' => 5000,
            ],
            [
                'nama' => 'Pantai Pondok Bali Pamanukan',
                'kategori' => 'Alam',
                'deskripsi' => 'Wisata pesisir utara Subang yang menyuguhkan pemandangan sunset indah di tepi Laut Jawa serta kuliner ikan bakar.',
                'alamat' => 'Desa Mayangan, Kecamatan Legonkulon / Pamanukan, Kabupaten Subang, Jawa Barat 41254',
                'lat' => -6.208765,
                'lng' => 107.828765,
                'pengelola' => 'Bapak Ujang Sumarna',
                'email' => 'pondokbali@gmail.com',
                'telp' => '081299008822',
                'tiket' => 15000,
            ],
            [
                'nama' => 'Curug Capolaga',
                'kategori' => 'Alam',
                'deskripsi' => 'Kawasan wisata sungai berbatu bersih dengan beberapa titik air terjun alami dan bumi perkemahan yang teduh.',
                'alamat' => 'Desa Panaruban, Kecamatan Sagalaherang, Kabupaten Subang, Jawa Barat 41282',
                'lat' => -6.675000,
                'lng' => 107.618000,
                'pengelola' => 'Pak Cecep',
                'email' => 'capolagacamp@gmail.com',
                'telp' => '081399887711',
                'tiket' => 15000,
            ],
            [
                'nama' => 'Mata Air Cimincul',
                'kategori' => 'Alam',
                'deskripsi' => 'Kolam renang air tawar alami yang sangat jernih bersumber dari mata air tanah asli dikelilingi hamparan sawah hijau.',
                'alamat' => 'Desa Pasanggrahan, Kecamatan Kasomalang, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.690000,
                'lng' => 107.755000,
                'pengelola' => 'Pak Nanang',
                'email' => 'ciminculspring@gmail.com',
                'telp' => '085211223399',
                'tiket' => 15000,
            ],
            [
                'nama' => 'Mata Air Kasumber',
                'kategori' => 'Alam',
                'deskripsi' => 'Destinasi wisata pemandian air alami yang super bening dan menyegarkan di kawasan Kasomalang Subang.',
                'alamat' => 'Desa Kasomalang Kulon, Kecamatan Kasomalang, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.685000,
                'lng' => 107.749000,
                'pengelola' => 'Ibu Yanti',
                'email' => 'kasumberspring@gmail.com',
                'telp' => '087722334455',
                'tiket' => 10000,
            ],
            [
                'nama' => 'Curug Sadim',
                'kategori' => 'Alam',
                'deskripsi' => 'Air terjun tersembunyi yang eksotis di tengah kawasan perkebunan teh Ciater, menawarkan ketenangan alam.',
                'alamat' => 'Desa Cicadas, Kecamatan Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.725000,
                'lng' => 107.675000,
                'pengelola' => 'Pak Deden',
                'email' => 'curugsadim@gmail.com',
                'telp' => '089611223344',
                'tiket' => 12500,
            ],
            [
                'nama' => 'Museum Wisma Karya',
                'kategori' => 'Budaya',
                'deskripsi' => 'Gedung bersejarah peninggalan kolonial Belanda yang kini digunakan sebagai museum penyimpanan artefak purbakala Subang.',
                'alamat' => 'Jl. Ade Irma Suryani, Kec. Subang, Kabupaten Subang, Jawa Barat 41211',
                'lat' => -6.569500,
                'lng' => 107.761000,
                'pengelola' => 'Dinas Pariwisata Subang',
                'email' => 'wismakarya@subang.go.id',
                'telp' => '(0260) 411012',
                'tiket' => 5000,
            ],
            [
                'nama' => 'Smart Hill Camp Ciater',
                'kategori' => 'Buatan',
                'deskripsi' => 'Destinasi foto jembatan kaca panjang di atas hamparan kebun teh hijau luas serta area berkemah eksklusif.',
                'alamat' => 'Desa Cisaat, Kecamatan Ciater, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.728000,
                'lng' => 107.679000,
                'pengelola' => 'Pak Rully',
                'email' => 'smarthillciater@gmail.com',
                'telp' => '081233221100',
                'tiket' => 15000,
            ],
            [
                'nama' => 'Pantai Kelapa Patimban',
                'kategori' => 'Alam',
                'deskripsi' => 'Pantai pesisir dengan pohon kelapa rindang dekat pelabuhan internasional Patimban yang memadukan industri & rekreasi.',
                'alamat' => 'Desa Patimban, Kecamatan Pusakanagara, Kabupaten Subang, Jawa Barat 41255',
                'lat' => -6.240000,
                'lng' => 107.900000,
                'pengelola' => 'Pak Warno',
                'email' => 'patimbanpantai@gmail.com',
                'telp' => '087811993344',
                'tiket' => 10000,
            ],
            [
                'nama' => 'Chakiji Green Valley',
                'kategori' => 'Buatan',
                'deskripsi' => 'Kolam renang rekreasi keluarga yang sejuk di tengah persawahan dan perbukitan Jalancagak.',
                'alamat' => 'Desa Curugrendeng, Kecamatan Jalancagak, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.662000,
                'lng' => 107.695000,
                'pengelola' => 'Pak Dani',
                'email' => 'chakijivalley@gmail.com',
                'telp' => '081299883344',
                'tiket' => 20000,
            ],
            [
                'nama' => 'Curug Kaliangkrek Kasomalang',
                'kategori' => 'Petualangan',
                'deskripsi' => 'Air terjun tersembunyi dengan aliran air deras yang jernih di tebing batu vulkanik Kasomalang.',
                'alamat' => 'Desa Kasomalang, Kecamatan Kasomalang, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.702000,
                'lng' => 107.762000,
                'pengelola' => 'Pak Mamat',
                'email' => 'kaliangkrek@gmail.com',
                'telp' => '081399887755',
                'tiket' => 10000,
            ],
            [
                'nama' => 'Desa Wisata Bunihayu',
                'kategori' => 'Budaya',
                'deskripsi' => 'Desa wisata budaya pelestari kesenian Sunda, pertanian organik terpadu, serta lokasi outbound alam bebas.',
                'alamat' => 'Desa Bunihayu, Kecamatan Jalancagak, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.655000,
                'lng' => 107.712000,
                'pengelola' => 'Bapak Sulaeman',
                'email' => 'bunihayuculture@gmail.com',
                'telp' => '085299008811',
                'tiket' => 15000,
            ],
            [
                'nama' => 'Kebun Nanas Jalancagak',
                'kategori' => 'Buatan',
                'deskripsi' => 'Agrowisata perkebunan nanas madu khas Subang. Pengunjung dapat memetik nanas segar langsung dari kebun rakyat.',
                'alamat' => 'Kecamatan Jalancagak, Kabupaten Subang, Jawa Barat 41281',
                'lat' => -6.669000,
                'lng' => 107.701000,
                'pengelola' => 'Koperasi Nanas Subang',
                'email' => 'nanasjalancagak@gmail.com',
                'telp' => '081311002233',
                'tiket' => 5000,
            ],
        ];

        foreach ($wisataList as $wItem) {
            PendaftaranPariwisata::create([
                'nama_wisata'    => $wItem['nama'],
                'kategori'       => $wItem['kategori'],
                'deskripsi'      => $wItem['deskripsi'],
                'alamat'         => $wItem['alamat'],
                'latitude'       => $wItem['lat'],
                'longitude'      => $wItem['lng'],
                'nama_pengelola' => $wItem['pengelola'],
                'email_kontak'   => $wItem['email'],
                'no_telp'        => $wItem['telp'],
                'jam_buka'       => '08:00:00',
                'jam_tutup'      => '17:00:00',
                'harga_tiket'    => $wItem['tiket'],
                'status_review'  => 'disetujui',
            ]);
        }


        // 4. JADWAL DOKTER JAGA (Tabel: jadwal_dokters)
        $this->command->info('Membuat Jadwal Dokter untuk Faskes...');
        
        $dokterTemplates = [
            ['nama' => 'dr. Cecep Hermawan, Sp.PD', 'spesialisasi' => 'Spesialis Penyakit Dalam'],
            ['nama' => 'drg. Fitri Handayani', 'spesialisasi' => 'Dokter Gigi'],
            ['nama' => 'dr. Siti Rahmawati', 'spesialisasi' => 'Dokter Umum'],
            ['nama' => 'dr. Budi Santoso, Sp.B', 'spesialisasi' => 'Spesialis Bedah'],
            ['nama' => 'dr. Ahmad Sobari, Sp.A', 'spesialisasi' => 'Spesialis Anak'],
            ['nama' => 'dr. H. Rudi Setiawan, Sp.OG', 'spesialisasi' => 'Spesialis Kandungan'],
            ['nama' => 'drg. Hendra Wijaya', 'spesialisasi' => 'Dokter Gigi'],
            ['nama' => 'dr. Nia Ramadhani', 'spesialisasi' => 'Dokter Umum Jaga'],
            ['nama' => 'dr. Rian Irawan', 'spesialisasi' => 'Dokter Umum Jaga'],
        ];

        foreach ($insertedFaskes as $faskes) {
            // Berikan 3 dokter per faskes
            $selectedDocs = $faker->randomElements($dokterTemplates, 3);
            foreach ($selectedDocs as $doc) {
                JadwalDokter::create([
                    'faskes_id' => $faskes->id,
                    'nama_dokter' => $doc['nama'],
                    'spesialisasi' => $doc['spesialisasi'],
                    'hari' => $faker->randomElements(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], mt_rand(2, 5)),
                    'jam_mulai' => $faker->randomElement(['08:00:00', '09:00:00', '14:00:00', '16:00:00']),
                    'jam_selesai' => $faker->randomElement(['13:00:00', '14:00:00', '19:00:00', '21:00:00']),
                ]);
            }
        }


        // 5. RIWAYAT KUNJUNGAN MEDIS WISATAWAN (Tabel: riwayat_kunjungan)
        $this->command->info('Membuat Riwayat Kunjungan Wisatawan...');
        
        $catatanKunjungan = [
            'Pertolongan pertama kram otot kaki saat berendam air panas.',
            'Cek tensi dan konsultasi kelelahan fisik pasca berkendara jauh.',
            'Tebus obat antialergi gatal akibat debu kebun teh.',
            'Penanganan luka gores ringan saat mendaki tebing curug.',
            'Membeli obat pereda pusing dan mual untuk keluarga.',
            'Cek kesehatan gigi darurat karena sakit gigi mendadak.',
            'Penanganan awal sesak napas ringan karena udara dingin kawah.',
            'Beli plester luka, antiseptic, dan perban cadangan.'
        ];

        foreach ($users as $user) {
            // Tiap user berkunjung ke 1-2 faskes
            $visitedFaskes = $faker->randomElements($insertedFaskes, mt_rand(1, 2));
            foreach ($visitedFaskes as $faskes) {
                RiwayatKunjungan::create([
                    'user_id' => $user->id,
                    'faskes_id' => $faskes->id,
                    'tanggal_kunjungan' => Carbon::now()->subDays(mt_rand(1, 28)),
                    'label_warna' => $faker->randomElement(['green', 'red', 'blue']),
                    'catatan_pribadi' => $faker->randomElement($catatanKunjungan),
                ]);
            }
        }


        // 6. ULASAN & REVIEW FASKES (Tabel: ulasan_faskes)
        $this->command->info('Membuat Ulasan & Testimoni Wisatawan...');
        
        $ulasanTemplates = [
            'Pelayanannya cepat sekali. Sangat menolong saat obat alergi dingin anak saya tertinggal di hotel.',
            'Petugas UGD sigap merawat luka robek kaki saya saat tergelincir di curug. Terima kasih banyak!',
            'Dokter umum ramah, ruang tunggu bersih. Sangat direkomendasikan jika cari faskes dekat Ciater.',
            'Apotek lengkap, harga obat transparan. Apoteker menjelaskan dosis pemakaian dengan sangat sabar.',
            'Faskes ini menerima BPJS aktif. Proses administrasinya cepat tanpa ribet.',
            'Dokter gigi telaten menangani anak kecil yang rewel. Pelayanan luar biasa ramah.',
            'Penanganan darurat malam hari berjalan lancar. Dokter dan perawat siaga di pos masing-masing.',
            'Sangat membantu bagi wisatawan yang bingung mencari faskes terpercaya di daerah asing.',
        ];

        foreach ($insertedFaskes as $faskes) {
            // Tiap faskes dapat 2-4 ulasan
            $reviewers = $faker->randomElements($users, mt_rand(2, 4));
            foreach ($reviewers as $reviewer) {
                UlasanFaskes::create([
                    'user_id' => $reviewer->id,
                    'faskes_id' => $faskes->id,
                    'rating' => $faker->numberBetween(4, 5),
                    'komentar' => $faker->randomElement($ulasanTemplates),
                ]);
            }
        }


        // 7. LAPORAN MASALAH / ERROR SPASIAL (Tabel: laporan_masalah)
        $this->command->info('Membuat Laporan Masalah Spasial...');
        
        $laporanTemplates = [
            [
                'subjek' => 'Akurasi Peta Leaflet',
                'deskripsi' => 'Pintu gerbang UGD bergeser sekitar 15 meter dari titik marker yang tertera di peta WanderMed.',
            ],
            [
                'subjek' => 'Nomor Kontak Darurat',
                'deskripsi' => 'Nomor telepon faskes sibuk terus ketika dihubungi saat akhir pekan malam hari.',
            ],
            [
                'subjek' => 'Fasilitas Layanan',
                'deskripsi' => 'Poli spesialis dalam informasi tertulis buka hari Sabtu, namun saat didatangi ternyata libur.',
            ],
            [
                'subjek' => 'Akurasi Alamat',
                'deskripsi' => 'Nama jalan pada alamat faskes tertulis Gang Dahlia, sedangkan plang resminya adalah Jl. Dahlia II.',
            ],
        ];

        // Buat 10 laporan masalah acak
        for ($i = 0; $i < 10; $i++) {
            $lap = $faker->randomElement($laporanTemplates);
            LaporanMasalah::create([
                'user_id' => $faker->randomElement($users)->id,
                'faskes_id' => $faker->randomElement($insertedFaskes)->id,
                'subjek' => $lap['subjek'],
                'deskripsi' => $lap['deskripsi'],
                'status' => $faker->randomElement(['pending', 'resolved']),
            ]);
        }


        // 8. SIMULASI CHAT REAL-TIME KOORDINASI DARURAT (Tabel: messages)
        $this->command->info('Membuat Percakapan Chat Koordinasi (Admin & Mitra)...');

        $chatScripts = [
            [
                ['role' => 'mitra', 'msg' => 'Selamat siang Admin WanderMed. Kami dari perwakilan faskes ingin menanyakan terkait proses approval edit fasilitas penunjang di dashboard.'],
                ['role' => 'admin', 'msg' => 'Selamat siang. Untuk permohonan edit fasilitas faskes akan kami verifikasi dalam waktu maksimal 1x24 jam sejak disimpan.'],
                ['role' => 'mitra', 'msg' => 'Baik, dokumen izin operasional radiologi terbaru sudah kami upload di form profil. Mohon dibantu review ya.'],
                ['role' => 'admin', 'msg' => 'Baik Pak, berkas sudah kami terima. Segera kami approve agar wisatawan bisa melihat layanan Radiologi Anda di peta.'],
            ],
            [
                ['role' => 'mitra', 'msg' => 'Halo Admin, pintu gerbang utama UGD Puskesmas kami sedang dialihkan karena perbaikan aspal jalan utama.'],
                ['role' => 'admin', 'msg' => 'Halo. Terima kasih laporannya. Apakah ada petunjuk arah alternatif atau koordinat gerbang sementara yang perlu kami ubah?'],
                ['role' => 'mitra', 'msg' => 'Pintu masuk sementara dipindah ke gerbang samping timur (jarak 20m). Kami sudah update kolom pengumuman faskes.'],
                ['role' => 'admin', 'msg' => 'Sempurna. Pengumuman sudah live di peta. Wisatawan yang mencari rute ke tempat Anda akan langsung melihat catatan tersebut.'],
            ],
            [
                ['role' => 'mitra', 'msg' => 'Selamat malam Admin, stok tabung oksigen darurat kami malam ini sedang menipis karena rujukan pasien kecelakaan yang meningkat.'],
                ['role' => 'admin', 'msg' => 'Malam. Terima kasih infonya. Kami akan arahkan rujukan ambulans berikutnya menuju faskes terdekat alternatif yang stoknya melimpah.'],
                ['role' => 'mitra', 'msg' => 'Terima kasih kerjasamanya Admin. Koordinasi ini sangat membantu meredakan antrean UGD kami.'],
            ],
            [
                ['role' => 'mitra', 'msg' => 'Selamat pagi Admin, apakah sistem WanderMed mendukung integrasi pendaftaran antrean faskes secara online?'],
                ['role' => 'admin', 'msg' => 'Pagi. Saat ini kami fokus pada pemetaan kesiapan layanan operasional dan navigasi spasial rute tercepat darurat.'],
                ['role' => 'mitra', 'msg' => 'Oh baik, paham. Berarti untuk antrean kami tetap melampirkan link antrean eksternal di kolom pengumuman ya.'],
                ['role' => 'admin', 'msg' => 'Betul sekali, silakan cantumkan link atau nomor WhatsApp antrean di bagian pengumuman faskes Anda.'],
            ],
        ];

        // Sebarkan percakapan ini ke beberapa mitra acak
        $selectedMitras = $faker->randomElements($mitraList, count($chatScripts));
        foreach ($selectedMitras as $idx => $mitra) {
            $script = $chatScripts[$idx];
            $timeBase = Carbon::now()->subHours(count($script) * 2);

            foreach ($script as $stepIdx => $chat) {
                Message::create([
                    'sender_role'   => $chat['role'],
                    'mitra_id'      => $mitra->id,
                    'body'          => $chat['msg'],
                    'read_by_mitra' => true,
                    'read_by_admin' => true,
                    'created_at'    => $timeBase->copy()->addMinutes($stepIdx * 15),
                ]);
            }
        }

        $this->command->info('===================================================');
        $this->command->info('SEEDING SELESAI! Database WanderMed siap digunakan.');
        $this->command->info('Informasi login administrator WanderMed (Hardcoded):');
        $this->command->info('Email: adminwandermed@gmail.com');
        $this->command->info('Password: admin123');
        $this->command->info('===================================================');
    }
}
