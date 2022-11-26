<?php

namespace Database\Factories\Commands;

use App\Models\Queries\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commands\Media>
 */
class MediaFactory extends Factory
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
            'name' => fake()->uuid(),
            'url' => fake()->imageUrl(),
            'entity' => 'Asset',
            'entity_id' => (fake()->randomElement(Asset::all()->toArray()))['id'],
            'type' => 'image',
        ];
    }
}
