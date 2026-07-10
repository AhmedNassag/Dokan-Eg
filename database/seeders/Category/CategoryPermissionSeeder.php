<?php

namespace Database\Seeders\Category;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CategoryPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-category',
            'show-category',
            'store-category',
            'update-category',
            'destroy-category',
        ];


        
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
