<?php

namespace Database\Seeders\Country;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Database\Seeders\Country\CountryPermissionSeeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CountryPermissionSeeder::class);

        Country::create(['name' => 'مصر', 'status' => true]);
        Country::create(['name' => 'السعودية', 'status' => true]);
        Country::create(['name' => 'الإمارات', 'status' => true]);
    }
}
