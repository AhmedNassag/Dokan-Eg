<?php

namespace Database\Seeders\Shop;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ShopPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-shop',
            'show-shop',
            'store-shop',
            'update-shop',
            'destroy-shop',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
