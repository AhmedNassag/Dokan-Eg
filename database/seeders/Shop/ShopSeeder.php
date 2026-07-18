<?php

namespace Database\Seeders\Shop;

use App\Models\Shop;
use Illuminate\Database\Seeder;
use Database\Seeders\Shop\ShopPermissionSeeder;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ShopPermissionSeeder::class);

        $shops = [
            [
                'user_id'      => 2,
                'name'         => 'المتجر الإلكتروني المصري',
                'slug'         => 'egyptian-online-store',
                'description'  => 'متجر إلكتروني يقدم منتجات متنوعة',
                'theme'        => 'default',
                'font_family'  => 'Cairo',
                'is_active'    => true,
                'is_featured'  => true,
                'published_at' => now(),
            ],
            [
                'user_id'      => 3,
                'name'         => 'سوق السعودية',
                'slug'         => 'saudi-market',
                'description'  => 'متجر إلكتروني سعودي',
                'theme'        => 'modern',
                'font_family'  => 'Tajawal',
                'is_active'    => true,
                'is_featured'  => false,
                'published_at' => now(),
            ],
        ];

        foreach ($shops as $data) {
            Shop::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
