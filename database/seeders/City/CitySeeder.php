<?php

namespace Database\Seeders\City;

use App\Models\City;
use Illuminate\Database\Seeder;
use Database\Seeders\City\CityPermissionSeeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CityPermissionSeeder::class);

        City::create(['name' => 'القاهرة', 'status' => true, 'country_id' => 1]);
        City::create(['name' => 'الإسكندرية', 'status' => true, 'country_id' => 1]);
        City::create(['name' => 'الجيزة', 'status' => true, 'country_id' => 1]);
        City::create(['name' => 'الرياض', 'status' => true, 'country_id' => 2]);
        City::create(['name' => 'جدة', 'status' => true, 'country_id' => 2]);
        City::create(['name' => 'دبي', 'status' => true, 'country_id' => 3]);
        City::create(['name' => 'أبوظبي', 'status' => true, 'country_id' => 3]);
    }
}
