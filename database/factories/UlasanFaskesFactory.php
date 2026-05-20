<?php

namespace Database\Factories;

use App\Models\UlasanFaskes;
use App\Models\User;
use App\Models\Faskes;
use Illuminate\Database\Eloquent\Factories\Factory;

class UlasanFaskesFactory extends Factory
{
    protected $model = UlasanFaskes::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'faskes_id' => Faskes::factory(),
            'rating' => $this->faker->numberBetween(4, 5),
            'komentar' => $this->faker->sentence(),
        ];
    }
}
