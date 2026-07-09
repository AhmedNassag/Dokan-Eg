<?php

namespace Database\Seeders\Translation;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Database\Seeder;
use Database\Seeders\Translation\TranslationPermissionSeeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(TranslationPermissionSeeder::class);

        $locales = [
            'en' => database_path('seeders/Translation/data/en.json'),
            'ar' => database_path('seeders/Translation/data/ar.json'),
        ];

        foreach ($locales as $code => $path) {
            $language = Language::where('code', $code)->first();
            if (!$language) continue;
            if (!file_exists($path)) continue;

            $data = json_decode(file_get_contents($path), true);
            if (!$data) continue;

            foreach ($data as $group => $value) {
                if (is_array($value)) {
                    $this->seedNested($language->id, $group, $value);
                } else {
                    Translation::firstOrCreate(
                        ['language_id' => $language->id, 'group' => null, 'key' => $group],
                        ['value' => $value]
                    );
                }
            }
        }
    }


    
    private function seedNested($languageId, $group, array $items, $prefix = '')
    {
        foreach ($items as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            if (is_array($value)) {
                $this->seedNested($languageId, $group, $value, $fullKey);
            } else {
                Translation::firstOrCreate(
                    ['language_id' => $languageId, 'group' => $group, 'key' => $fullKey],
                    ['value' => $value]
                );
            }
        }
    }
}
