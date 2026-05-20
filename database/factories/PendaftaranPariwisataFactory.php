<?php

namespace Database\Factories;

use App\Models\PendaftaranPariwisata;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendaftaranPariwisataFactory extends Factory
{
    protected $model = PendaftaranPariwisata::class;

    public function definition(): array
    {
        return [
            'nama_wisata' => $this->faker->words(3, true),
            'kategori' => $this->faker->randomElement(['Alam', 'Budaya', 'Buatan', 'Kuliner', 'Petualangan']),
            'deskripsi' => $this->faker->paragraph(),
            'alamat' => $this->faker->address(),
            'foto_path' => null,
            'latitude' => $this->faker->latitude(-6.75, -6.20),
            'longitude' => $this->faker->longitude(107.60, 107.90),
            'nama_pengelola' => $this->faker->name(),
            'email_kontak' => $this->faker->unique()->safeEmail(),
            'no_telp' => $this->faker->phoneNumber(),
            'jam_buka' => '08:00:00',
            'jam_tutup' => '17:00:00',
            'harga_tiket' => $this->faker->randomElement([5000, 10000, 15000, 20000, 30000, 35000]),
            'status_review' => 'disetujui',
            'catatan_admin' => null,
        ];
    }
}
