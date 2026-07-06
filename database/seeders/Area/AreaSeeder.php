<?php

namespace Database\Seeders\Area;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AreaPermissionSeeder::class);

        $areas = [
            ['name' => 'وسط البلد', 'status' => true, 'city_id' => 1],
            ['name' => 'مدينة نصر', 'status' => true, 'city_id' => 1],
            ['name' => 'المهندسين', 'status' => true, 'city_id' => 2],
            ['name' => 'الملز', 'status' => true, 'city_id' => 3],
            ['name' => 'العليا', 'status' => true, 'city_id' => 4],
            ['name' => 'ديرة', 'status' => true, 'city_id' => 5],
            ['name' => 'الريم', 'status' => true, 'city_id' => 6],
        ];

        foreach ($areas as $data) {
            Area::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
