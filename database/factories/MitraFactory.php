<?php

namespace Database\Factories;

use App\Models\Mitra;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MitraFactory extends Factory
{
    protected $model = Mitra::class;

    public function definition(): array
    {
        return [
            'nama_penanggung_jawab' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('mitra123'),
            'no_telp' => $this->faker->phoneNumber(),
            'jenis_mitra' => $this->faker->randomElement(['faskes', 'pariwisata']),
            'is_verified' => true,
            'is_active' => true,
            'blocking_reason' => null,
            'catatan_admin' => null,
            'remember_token' => Str::random(10),
        ];
    }
}
