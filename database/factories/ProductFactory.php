<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(12),
            'price' => $this->faker->randomFloat(2, 1990, 49990),
            'image' => null,
        ];
    }
}
