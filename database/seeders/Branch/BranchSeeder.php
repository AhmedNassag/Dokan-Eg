<?php

namespace Database\Seeders\Branch;

use App\Models\Branch;
use Illuminate\Database\Seeder;
use Database\Seeders\Branch\BranchPermissionSeeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(BranchPermissionSeeder::class);

        Branch::create(['name' => 'فرع القاهرة الرئيسي', 'status' => true, 'code' => 'BR-CAI-01', 'mobile' => '01000000001', 'address' => 'شارع 26 يوليو', 'area_id' => 1]);
        Branch::create(['name' => 'فرع مدينة نصر', 'status' => true, 'code' => 'BR-CAI-02', 'mobile' => '01000000002', 'address' => 'شارع عباس العقاد', 'area_id' => 2]);
        Branch::create(['name' => 'فرع المهندسين', 'status' => true, 'code' => 'BR-CAI-03', 'mobile' => '01000000003', 'address' => 'شارع البطل أحمد عبدالعزيز', 'area_id' => 3]);
        Branch::create(['name' => 'فرع الرياض', 'status' => true, 'code' => 'BR-RUH-01', 'mobile' => '0500000001', 'address' => 'شارع التحلية', 'area_id' => 4]);
        Branch::create(['name' => 'فرع دبي', 'status' => true, 'code' => 'BR-DXB-01', 'mobile' => '0500000002', 'address' => 'شارع الشيخ زايد', 'area_id' => 6]);
    }
}
