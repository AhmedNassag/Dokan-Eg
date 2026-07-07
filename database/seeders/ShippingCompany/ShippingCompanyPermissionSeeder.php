<?php

namespace Database\Seeders\ShippingCompany;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ShippingCompanyPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-shipping-company', 'store-shipping-company', 'show-shipping-company',
            'update-shipping-company', 'destroy-shipping-company',
        ];
        foreach ($permissions as $p) {
            Permission::findOrCreate($p);
        }
    }
}
