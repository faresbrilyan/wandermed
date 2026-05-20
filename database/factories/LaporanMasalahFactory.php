<?php

namespace Database\Factories;

use App\Models\LaporanMasalah;
use App\Models\User;
use App\Models\Faskes;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanMasalahFactory extends Factory
{
    protected $model = LaporanMasalah::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'faskes_id' => Faskes::factory(),
            'subjek' => $this->faker->randomElement(['Akurasi Koordinat Peta', 'Fasilitas Layanan', 'Nomor Kontak', 'Jadwal Buka/Tutup']),
            'deskripsi' => $this->faker->paragraph(),
            'status' => 'pending',
        ];
    }
}
