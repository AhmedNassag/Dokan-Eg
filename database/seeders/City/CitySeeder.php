<?php

namespace Database\Seeders\City;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CityPermissionSeeder::class);

        $cities = [
            ['name' => 'القاهرة', 'status' => true, 'country_id' => 1],
            ['name' => 'الجيزة', 'status' => true, 'country_id' => 1],
            ['name' => 'الرياض', 'status' => true, 'country_id' => 2],
            ['name' => 'جدة', 'status' => true, 'country_id' => 2],
            ['name' => 'دبي', 'status' => true, 'country_id' => 3],
            ['name' => 'أبوظبي', 'status' => true, 'country_id' => 3],
        ];

        foreach ($cities as $data) {
            City::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
