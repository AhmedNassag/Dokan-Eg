<?php

namespace Database\Seeders\Brand;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BrandPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-brand',
            'store-brand',
            'show-brand',
            'update-brand',
            'destroy-brand',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
