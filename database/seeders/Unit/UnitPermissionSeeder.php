<?php

namespace Database\Seeders\Unit;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UnitPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-unit',
            'store-unit',
            'show-unit',
            'update-unit',
            'destroy-unit',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
