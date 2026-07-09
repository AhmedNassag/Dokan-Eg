<?php

namespace Database\Seeders\Translation;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class TranslationPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'list-translation',
            'show-translation',
            'store-translation',
            'update-translation',
            'destroy-translation'
        ];



        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
