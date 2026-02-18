<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AgentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'contact_number' => $this->faker->phoneNumber(),
            'commission_rate' => $this->faker->randomFloat(2, 5, 15), // 5% to 15%
            'bank_details' => $this->faker->bankAccountNumber() . ' - ' . $this->faker->company(),
        ];
    }
}
