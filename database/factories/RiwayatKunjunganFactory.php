<?php

namespace Database\Factories;

use App\Models\RiwayatKunjungan;
use App\Models\User;
use App\Models\Faskes;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiwayatKunjunganFactory extends Factory
{
    protected $model = RiwayatKunjungan::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'faskes_id' => Faskes::factory(),
            'tanggal_kunjungan' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'label_warna' => $this->faker->randomElement(['green', 'red', 'blue']),
            'catatan_pribadi' => $this->faker->sentence(),
        ];
    }
}
