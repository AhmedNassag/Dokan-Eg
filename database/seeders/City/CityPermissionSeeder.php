<?php

namespace Database\Seeders\City;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CityPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-city',
            'store-city',
            'show-city',
            'update-city',
            'destroy-city',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
