<?php

namespace Database\Seeders\ShippingCompany;

use App\Models\ShippingCompany;
use App\Models\ShippingCompanyPrice;
use Illuminate\Database\Seeder;
use Database\Seeders\ShippingCompany\ShippingCompanyPermissionSeeder;

class ShippingCompanySeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ShippingCompanyPermissionSeeder::class);

        $shippingCompany1 =ShippingCompany::create(['name' => 'شركة الشحن السريع', 'code' => 'SHIP-001', 'phone' => '01000000100','status' => true]);
        $shippingCompany2 =ShippingCompany::create(['name' => 'شركة البريد الممتاز', 'code' => 'SHIP-002', 'phone' => '01000000200','status' => true]);

        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany1->id, 'city_id' => 1, 'price' => 100]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany1->id, 'city_id' => 2, 'price' => 200]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany1->id, 'city_id' => 3, 'price' => 300]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany1->id, 'city_id' => 4, 'price' => 400]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany1->id, 'city_id' => 5, 'price' => 500]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany1->id, 'city_id' => 6, 'price' => 600]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany1->id, 'city_id' => 7, 'price' => 700]);

        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany2->id, 'city_id' => 1, 'price' => 150]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany2->id, 'city_id' => 2, 'price' => 250]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany2->id, 'city_id' => 3, 'price' => 350]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany2->id, 'city_id' => 4, 'price' => 450]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany2->id, 'city_id' => 5, 'price' => 550]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany2->id, 'city_id' => 6, 'price' => 650]);
        ShippingCompanyPrice::create(['shipping_company_id' => $shippingCompany2->id, 'city_id' => 7, 'price' => 750]);
    }
}
