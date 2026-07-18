<?php

namespace Database\Seeders;

use Database\Seeders\Area\AreaSeeder;
use Database\Seeders\Branch\BranchSeeder;
use Database\Seeders\Category\CategorySeeder;
use Database\Seeders\Language\LanguageSeeder;
use Database\Seeders\ShippingCompany\ShippingCompanySeeder;
use Database\Seeders\Shop\ShopSeeder;
use Database\Seeders\Translation\TranslationSeeder;
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
            'list-user',
            'show-user',
            'store-user',
            'update-user',
            'destroy-user',
            'list-role',
            'show-role',
            'store-role',
            'update-role',
            'destroy-role',
            'list-permission',
        ];

        foreach ($permissionNames as $name) {
            Permission::findOrCreate($name);
        }

        $this->call(UserSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(TranslationSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(BranchSeeder::class);
        $this->call(ShippingCompanySeeder::class);
        $this->call(ShopSeeder::class);
        
        $this->call(PermissionSeeder::class);
    }
}
