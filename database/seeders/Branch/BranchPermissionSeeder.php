<?php

namespace Database\Seeders\Branch;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BranchPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'list-branch',
            'show-branch',
            'store-branch',
            'update-branch',
            'destroy-branch',
        ];



        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }
    }
}
