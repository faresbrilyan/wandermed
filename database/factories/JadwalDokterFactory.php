<?php

namespace Database\Factories;

use App\Models\JadwalDokter;
use App\Models\Faskes;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalDokterFactory extends Factory
{
    protected $model = JadwalDokter::class;

    public function definition(): array
    {
        return [
            'faskes_id' => Faskes::factory(),
            'nama_dokter' => $this->faker->name(),
            'spesialisasi' => $this->faker->randomElement(['Dokter Umum', 'Spesialis Anak', 'Spesialis Bedah', 'Dokter Gigi', 'Spesialis Penyakit Dalam']),
            'hari' => $this->faker->randomElement(['Senin - Jumat', 'Sabtu & Minggu', 'Setiap Hari', 'Senin - Rabu', 'Kamis - Sabtu']),
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '14:00:00',
        ];
    }
}
