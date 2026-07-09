<?php

namespace Database\Seeders\ShippingCompany;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ShippingCompanyPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-shippingCompany',
            'show-shippingCompany',
            'store-shippingCompany',
            'update-shippingCompany',
            'destroy-shippingCompany',
        ];

        foreach ($permissions as $p) {
            Permission::findOrCreate($p);
        }
    }
}
