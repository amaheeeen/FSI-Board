<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    public function definition(): array
    {
        $departure = $this->faker->dateTimeBetween('now', '+6 months');
        $return = (clone $departure)->modify('+9 days');
        
        $basePrice = $this->faker->randomElement([25000000, 28000000, 35000000]);

        return [
            'name' => 'Umrah ' . $this->faker->word() . ' ' . date('Y'),
            'departure_date' => $departure,
            'return_date' => $return,
            'price_quad' => $basePrice,
            'price_triple' => $basePrice + 1500000,
            'price_double' => $basePrice + 3000000,
            'hotel_makkah' => 'Makkah Hotel ' . $this->faker->word(),
            'hotel_madinah' => 'Madinah Hotel ' . $this->faker->word(),
            'quota' => 45,
            'status' => 'Open',
        ];
    }
}
