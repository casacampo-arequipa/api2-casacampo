<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_promotion' => fake()->words(3, true),
            'percentage' => fake()->randomFloat(0, 100),
            'description' => fake()->paragraph,
            'date_start' => fake()->dateTimeBetween('-1 month', 'now'),
            'date_end' => fake()->dateTimeBetween('now', '+1 month'),
            'state' => fake()->boolean,
        ];
    }
}
