<?php

namespace Database\Factories\Commands;

use App\Models\Queries\Leasing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\Rent>
 */
class RentFactory extends Factory
{
    const STATUS = ['PAID', 'OVERDUE'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => fake()->uuid(),
            'label' => fake()->uuid(),
            'month' => fake()->monthName(),
            'year' => fake()->year(),
            'status' => fake()->randomElement(self::STATUS),
            'amount' => fake()->numberBetween(60000, 3000000),
            'currency' => 'XOF',
            'paid_at' => fake()->boolean(30) ? fake()->dateTimeBetween('-1 month') : null,
            'leasing_id' => (fake()->randomElement(Leasing::all()->toArray()))['id']
        ];
    }
}
