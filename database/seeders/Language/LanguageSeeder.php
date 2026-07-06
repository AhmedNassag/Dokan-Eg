<?php

namespace Database\Seeders\Language;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(LanguagePermissionSeeder::class);

        $languages = [
            ['name' => 'English', 'code' => 'en', 'direction' => 'ltr', 'status' => true],
            ['name' => 'العربية', 'code' => 'ar', 'direction' => 'rtl', 'status' => true],
        ];

        foreach ($languages as $data) {
            Language::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
