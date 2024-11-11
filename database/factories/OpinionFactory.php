<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Opinion>
 */
class OpinionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'calification' => fake()->numberBetween(1, 5),  // Genera una calificación entre 1 y 5
            'date' => fake()->dateTimeBetween('-1 year', 'now'),  // Fecha aleatoria entre el último año y el momento actual
            'coment' => fake()->paragraph,  // Genera un párrafo como comentario
            'reservation_id' => fake()->numberBetween(1, 5),  // Genera un ID de reservación entre 1 y 5
        ];
        
    }
}
