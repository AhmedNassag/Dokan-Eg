<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = Permission::pluck('id','name')->all();

        //admin
        $admin     = User::where('email', 'admin@demo.com')->first();
        $adminRole = Role::findOrCreate('admin');
        $adminRole->syncPermissions($permissions);
        $admin->assignRole([$adminRole->id]);

        //merchant
        $merchant     = User::where('email', 'merchant@demo.com')->first();
        $merchantRole = Role::findOrCreate('merchant');
        $merchantRole->syncPermissions($permissions);
        $merchant->assignRole([$merchantRole->id]);

        //marketer
        $marketer     = User::where('email', 'marketer@demo.com')->first();
        $marketerRole = Role::findOrCreate('marketer');
        $marketerRole->syncPermissions($permissions);
        $marketer->assignRole([$marketerRole->id]);

        //shipping representative
        $shippingRep     = User::where('email', 'shipping@demo.com')->first();
        $shippingRepRole = Role::findOrCreate('shipping_representative');
        $shippingRepRole->syncPermissions($permissions);
        $shippingRep->assignRole([$shippingRepRole->id]);
    }
}
