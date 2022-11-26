<?php

namespace Database\Factories\Commands;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\Tenant>
 */
class TenantFactory extends Factory
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
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_type' => 'CNI',
            'document_id' => fake()->randomNumber(),
            'phone_number' => fake()->e164PhoneNumber(),
            'profession' => fake()->jobTitle(),
            'gender' => 'M',
            'nationality' => 'Ivorian',
            'marital_status' => 'SINGLE',
        ];
    }
}
