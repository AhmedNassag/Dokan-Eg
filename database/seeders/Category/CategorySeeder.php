<?php

namespace Database\Seeders\Category;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Seeders\Category\CategoryPermissionSeeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CategoryPermissionSeeder::class);

        $admin    = User::where('email', 'admin@demo.com')->first();
        $merchant = User::where('email', 'merchant@demo.com')->first();
        $marketer = User::where('email', 'marketer@demo.com')->first();

        Category::create(['name' => 'Admin Category 1', 'description' => 'Created by admin', 'created_by' => $admin->id]);
        Category::create(['name' => 'Admin Category 2', 'description' => 'Also created by admin', 'created_by' => $admin->id]);
        Category::create(['name' => 'Merchant Category 1', 'description' => 'Created by merchant', 'created_by' => $merchant->id]);
        Category::create(['name' => 'Merchant Category 2', 'description' => 'Another merchant category', 'created_by' => $merchant->id]);
        Category::create(['name' => 'Marketer Category 1', 'description' => 'Created by marketer', 'created_by' => $marketer->id]);
    }
}
