<?php

namespace Database\Seeders\Area;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AreaPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-area',
            'show-area',
            'store-area',
            'update-area',
            'destroy-area',
        ];



        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
