<?php

namespace Database\Seeders;

use App\Models\Commands\Leasing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeasingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Leasing::factory()
            ->count(1000)
            ->create();
    }
}
