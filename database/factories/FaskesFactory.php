<?php

namespace Database\Factories;

use App\Models\Faskes;
use App\Models\Mitra;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaskesFactory extends Factory
{
    protected $model = Faskes::class;

    public function definition(): array
    {
        return [
            'mitra_id' => Mitra::factory(),
            'nama_faskes' => $this->faker->company(),
            'jenis_faskes' => $this->faker->randomElement(['Rumah Sakit', 'Klinik', 'Apotek', 'Puskesmas']),
            'alamat' => $this->faker->address(),
            'no_telp' => $this->faker->phoneNumber(),
            'foto_path' => null,
            'latitude' => $this->faker->latitude(-6.75, -6.20),
            'longitude' => $this->faker->longitude(107.60, 107.90),
            'status_operasional' => 'open',
            'dukungan_bpjs' => $this->faker->boolean(70),
            'layanan_tersedia' => ['UGD 24 Jam', 'Ambulans', 'Poli Umum'],
            'pengumuman' => $this->faker->sentence(),
            'pesan_admin' => null,
        ];
    }
}
