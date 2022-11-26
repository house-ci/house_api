<?php

namespace Database\Factories\Commands;

use App\Models\Queries\City;
use App\Models\Queries\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\City>
 */
class CityFactory extends Factory
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
            'name' => fake()->city(),
            'country_id' => $countryId = (fake()->randomElement(Country::all()->toArray()))['id'],
            'parent_id' => fake()->boolean(40) ? @(fake()->randomElement(City::where('country_id', $countryId)->get()?->toArray()))['id'] ?? null : null,
        ];
    }
}
