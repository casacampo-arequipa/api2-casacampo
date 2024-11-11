<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cottage>
 */
class CottageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_cottage' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 50, 500), // Precio entre 50 y 500 con 2 decimales
            'capacity' => fake()->numberBetween(1, 10),
            'availability' => fake()->boolean(),
            'rooms' => fake()->numberBetween(1, 5), 
            'beds' => fake()->numberBetween(1, 10), 
            'bathrooms' => fake()->numberBetween(1, 3),
        ];
    }
}
