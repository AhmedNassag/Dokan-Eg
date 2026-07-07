<?php

namespace Database\Seeders\Country;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CountryPermissionSeeder::class);

        $countries = [
            ['name' => 'مصر', 'status' => true],
            ['name' => 'السعودية', 'status' => true],
            ['name' => 'الإمارات', 'status' => true],
        ];

        foreach ($countries as $data) {
            Country::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
