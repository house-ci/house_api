<?php

namespace Database\Factories\Commands;

use App\Models\Queries\RealEstate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\Asset>
 */
class AssetFactory extends Factory
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
            'number_of_rooms' => fake()->numberBetween(1, 10),
            'description' => fake()->sentence(10),
            'door_number' => fake()->word(10) . fake()->numberBetween(1, 10),
            'is_available' => fake()->boolean(85),
            'amount' => fake()->numberBetween(60000, 3000000),
            'currency' => 'XOF',
            'payment_deadline_day' => '5',
            'extras' => '{}',
            'real_estate_id' => (fake()->randomElement(RealEstate::all()->toArray()))['id']
        ];
    }
}
