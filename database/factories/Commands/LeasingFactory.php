<?php

namespace Database\Factories\Commands;

use App\Models\Queries\Asset;
use App\Models\Queries\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\Leasing>
 */
class LeasingFactory extends Factory
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
            'started_on' => fake()->dateTimeBetween('-2 year', '-2 months'),
            'ended_on' => fake()->boolean(20) ? fake()->dateTimeBetween('-1 month') : null,
            'amount' => fake()->numberBetween(60000, 3000000),
            'currency' => 'XOF',
            'payment_deadline_day' => '5',
            'agreement_url' => fake()->imageUrl(),
            'is_active' => fake()->boolean(90),

            'asset_id' => (fake()->randomElement(Asset::all()->toArray()))['id'],
            'tenant_id' => (fake()->randomElement(Tenant::all()->toArray()))['id'],
        ];
    }
}
