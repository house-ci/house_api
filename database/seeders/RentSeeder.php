<?php

namespace Database\Seeders;

use App\Models\Commands\Rent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rent::factory()
            ->count(700)
            ->create();
    }
}
