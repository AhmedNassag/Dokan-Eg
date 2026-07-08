<?php

namespace Database\Seeders;

use Database\Seeders\Area\AreaSeeder;
use Database\Seeders\Branch\BranchSeeder;
use Database\Seeders\Brand\BrandSeeder;
use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\Language\LanguageSeeder;
use Database\Seeders\ShippingCompany\ShippingCompanySeeder;
use Database\Seeders\Translation\TranslationSeeder;
use Database\Seeders\Unit\UnitSeeder;
use Database\Seeders\City\CitySeeder;
use Database\Seeders\Country\CountrySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

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
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(BranchSeeder::class);

        $this->call(LanguageSeeder::class);
        $this->call(TranslationSeeder::class);
        $this->call(ShippingCompanySeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(UnitSeeder::class);
    }
}