<?php

namespace Database\Factories\Commands;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => fake()->uuid(),
            'name' => fake()->country(),
            'iso2' => fake()->countryCode(),
            'prefix' => fake()->numberBetween(1, 255),
        ];
    }
}
