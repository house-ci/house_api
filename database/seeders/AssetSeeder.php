<?php

namespace Database\Seeders;

use App\Models\Commands\Asset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Asset::factory()
            ->count(300)
            ->create();
    }
}
