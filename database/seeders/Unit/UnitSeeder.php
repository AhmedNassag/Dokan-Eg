<?php

namespace Database\Seeders\Unit;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UnitPermissionSeeder::class);

        $units = [
            ['name' => 'Kilogram', 'status' => true, 'code' => 'KG', 'short_name' => 'kg', 'base_unit' => null, 'operator' => null, 'operator_value' => null],
            ['name' => 'Gram', 'status' => true, 'code' => 'GM', 'short_name' => 'g', 'base_unit' => null, 'operator' => '*', 'operator_value' => 1000],
            ['name' => 'Piece', 'status' => true, 'code' => 'PC', 'short_name' => 'pc', 'base_unit' => null, 'operator' => null, 'operator_value' => null],
            ['name' => 'Liter', 'status' => true, 'code' => 'LT', 'short_name' => 'L', 'base_unit' => null, 'operator' => null, 'operator_value' => null],
            ['name' => 'Milliliter', 'status' => true, 'code' => 'ML', 'short_name' => 'ml', 'base_unit' => null, 'operator' => '*', 'operator_value' => 1000],
        ];

        foreach ($units as $data) {
            Unit::firstOrCreate(['code' => $data['code']], $data);
        }
    }
}
