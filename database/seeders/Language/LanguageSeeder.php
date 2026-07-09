<?php

namespace Database\Seeders\Language;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Database\Seeders\Language\LanguagePermissionSeeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(LanguagePermissionSeeder::class);

        Language::create(['name' => 'العربية', 'code' => 'ar', 'direction' => 'rtl', 'status' => true, 'is_default' => true]);
        Language::create(['name' => 'English', 'code' => 'en', 'direction' => 'ltr', 'status' => true, 'is_default' => false]);
    }
}
