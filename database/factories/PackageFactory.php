<?php

namespace Database\Factories;

use App\Models\Cottage;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph,
            'max_person' => fake()->numberBetween(1, 50),
            'price_monday_to_thursday' => fake()->randomFloat(2, 100, 2000),
            'price_friday_to_sunday' => fake()->randomFloat(2, 150, 2500),
            'img' => fake()->imageUrl(640, 480, 'cats', true),
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Package $package) {
            // Asociar el paquete con 1 a 3 cabañas aleatorias
            $cottageIds = Cottage::inRandomOrder()->take(rand(1, 2))->pluck('id');
            $package->cottages()->attach($cottageIds);
        });
    }
}
