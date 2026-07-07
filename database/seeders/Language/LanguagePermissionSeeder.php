<?php

namespace Database\Seeders\Language;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class LanguagePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'list-language', 'store-language', 'show-language',
            'update-language', 'destroy-language',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
