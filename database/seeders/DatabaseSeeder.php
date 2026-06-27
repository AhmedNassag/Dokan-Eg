<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Database\Seeders\UserSeeder;
use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $permissionNames = [
            'read-dashboard',
            'list-role',
            'store-role',
            'show-role',
            'update-role',
            'destroy-role',
            'list-permission',
            'list-user',
            'store-user',
            'show-user',
            'update-user',
            'destroy-user',
        ];

        foreach ($permissionNames as $name) {
            Permission::findOrCreate($name);
        }

        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PermissionSeeder::class);
    }
}
