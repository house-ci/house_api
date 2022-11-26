<?php

namespace Database\Seeders;

use App\Models\Commands\RealEstate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealEstateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RealEstate::factory()
            ->count(100)
            ->create();
    }
}
