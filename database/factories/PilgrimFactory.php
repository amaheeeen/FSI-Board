<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PilgrimFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'),
            'passport_number' => $this->faker->unique()->bothify('??#######'),
            'address' => $this->faker->address(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
        ];
    }
}
