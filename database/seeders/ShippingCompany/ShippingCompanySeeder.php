<?php

namespace Database\Seeders\ShippingCompany;

use App\Models\ShippingCompany;
use App\Models\ShippingCompanyPrice;
use Illuminate\Database\Seeder;

class ShippingCompanySeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ShippingCompanyPermissionSeeder::class);

        $companies = [
            ['name' => 'شركة الشحن السريع', 'code' => 'SHIP-001', 'phone' => '01000000100', 'status' => true],
            ['name' => 'شركة البريد الممتاز', 'code' => 'SHIP-002', 'phone' => '01000000200', 'status' => true],
        ];

        foreach ($companies as $data) {
            ShippingCompany::firstOrCreate(['code' => $data['code']], $data);
        }

        $prices = [
            ['shipping_company_id' => 1, 'city_id' => 1, 'price' => 50],
            ['shipping_company_id' => 1, 'city_id' => 2, 'price' => 55],
            ['shipping_company_id' => 1, 'city_id' => 3, 'price' => 65],
            ['shipping_company_id' => 1, 'city_id' => 4, 'price' => 120],
            ['shipping_company_id' => 1, 'city_id' => 5, 'price' => 130],
            ['shipping_company_id' => 1, 'city_id' => 6, 'price' => 150],
        ];

        foreach ($prices as $data) {
            ShippingCompanyPrice::firstOrCreate(
                ['shipping_company_id' => $data['shipping_company_id'], 'city_id' => $data['city_id']],
                $data
            );
        }
    }
}
