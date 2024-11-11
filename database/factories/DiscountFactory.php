<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->text(20),
            'percentage' => fake()->randomFloat(0, 100),
            'date_start' => fake()->dateTimeBetween('-1 years', 'now'),
            'date_end' => fake()->dateTimeBetween('now', '+1 years'),
            'state' => fake()->boolean,
        ];
    }
}
