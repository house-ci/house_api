<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PropertyTypeSeeder::class,
            CountrySeeder::class,
            OwnerSeeder::class,
            CitySeeder::class,
            CitySeeder::class,// put this double so we can have city with parent
            RealEstateSeeder::class,
            AssetSeeder::class,
            MediaSeeder::class,
            TenantsSeeder::class,
            LeasingSeeder::class,
            RentSeeder::class,
        ]);
    }
}
