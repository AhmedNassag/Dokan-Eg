<?php

namespace Database\Seeders\Brand;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BrandPermissionSeeder::class);

        $brands = [
            ['name' => 'Sony', 'status' => true, 'code' => 'BR-SONY-01'],
            ['name' => 'Samsung', 'status' => true, 'code' => 'BR-SAMS-01'],
            ['name' => 'Apple', 'status' => true, 'code' => 'BR-APPL-01'],
            ['name' => 'Nike', 'status' => true, 'code' => 'BR-NIKE-01'],
            ['name' => 'Adidas', 'status' => true, 'code' => 'BR-ADID-01'],
        ];

        foreach ($brands as $data) {
            Brand::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
