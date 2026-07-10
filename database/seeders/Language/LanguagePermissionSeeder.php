<?php

namespace Database\Seeders\Language;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class LanguagePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'list-language',
            'show-language',
            'store-language',
            'update-language',
            'destroy-language',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
