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
            'list-country',
            'store-country',
            'show-country',
            'update-country',
            'destroy-country',
        ];


        
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
