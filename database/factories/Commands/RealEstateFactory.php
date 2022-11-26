<?php

namespace Database\Factories\Commands;

use App\Models\Queries\City;
use App\Models\Queries\Owner;
use App\Models\Queries\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\RealEstate>
 */
class RealEstateFactory extends Factory
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
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'number_of_floor' => fake()->numberBetween(1, 5),
            'lot' => fake()->numberBetween(10000, 1000000),
            'block' => fake()->numberBetween(10000, 1000000),
            'city_id' => (fake()->randomElement(City::all()->toArray()))['id'],
            'property_type_id' => (fake()->randomElement(PropertyType::all()->toArray()))['id'],
            'owner_id' => (fake()->randomElement(Owner::all()->toArray()))['id'],
        ];
    }
}
