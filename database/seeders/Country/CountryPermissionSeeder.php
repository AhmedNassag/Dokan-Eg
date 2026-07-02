<?php

namespace Database\Seeders\Country;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CountryPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-category',
            'store-category',
            'show-category',
            'update-category',
            'destroy-category',
        ];


        
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
