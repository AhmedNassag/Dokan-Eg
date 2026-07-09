<?php

namespace Database\Seeders\Area;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Database\Seeders\Area\AreaPermissionSeeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AreaPermissionSeeder::class);

        Area::create(['name' => 'وسط البلد', 'city_id' => 1]);
        Area::create(['name' => 'مدينة نصر', 'city_id' => 1]);
        Area::create(['name' => 'المهندسين', 'city_id' => 2]);
        Area::create(['name' => 'الملز', 'city_id' => 3]);
        Area::create(['name' => 'العليا', 'city_id' => 4]);
        Area::create(['name' => 'ديرة', 'city_id' => 5]);
        Area::create(['name' => 'الريم', 'city_id' => 6]);
    }
}
