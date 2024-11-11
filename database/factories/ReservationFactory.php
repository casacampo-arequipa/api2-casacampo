<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 10),
            'date_start' => fake()->dateTimeBetween('now', '+1 month'),
            'date_end' => fake()->dateTimeBetween('+1 month', '+2 months'),
            'total_price' => fake()->randomFloat(2, 100, 1000),
            'state' => fake()->boolean,
            'date_reservation' => fake()->dateTimeBetween('-1 month', 'now'),
            'cottage_id' => fake()->numberBetween(1, 4),
            'discount_id' => fake()->numberBetween(1, 10),
            'promotion_id' => fake()->numberBetween(1, 5),
        ];
    }
}
