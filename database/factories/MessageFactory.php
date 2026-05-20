<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Mitra;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'sender_role' => $this->faker->randomElement(['admin', 'mitra']),
            'mitra_id' => Mitra::factory(),
            'body' => $this->faker->sentence(),
            'read_by_mitra' => true,
            'read_by_admin' => true,
        ];
    }
}
