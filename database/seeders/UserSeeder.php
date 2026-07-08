<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Enums\Status;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name'      => 'Admin',
                'password'  => Hash::make('admin'),
                'user_type' => UserType::ADMIN,
                'status'    => Status::APPROVED,
            ]
        );
        $adminRole = Role::findOrCreate('admin');

        //merchant
        $merchant = User::firstOrCreate(
            ['email' => 'merchant@demo.com'],
            [
                'name'      => 'Merchant',
                'password'  => Hash::make('merchant'),
                'user_type' => UserType::MERCHANT,
                'status'    => Status::APPROVED,
            ]
        );
        $merchantRole = Role::findOrCreate('merchant');

        //marketer
        $marketer = User::firstOrCreate(
            ['email' => 'marketer@demo.com'],
            [
                'name'      => 'Marketer',
                'password'  => Hash::make('marketer'),
                'user_type' => UserType::MARKETER,
                'status'    => Status::APPROVED,
            ]
        );
        $marketerRole = Role::findOrCreate('marketer');

        //shipping representative
        $shippingRep = User::firstOrCreate(
            ['email' => 'shipping@demo.com'],
            [
                'name'      => 'Shipping Representative',
                'password'  => Hash::make('shipping'),
                'user_type' => UserType::SHIPPING_REPRESENTATIVE,
                'status'    => Status::APPROVED,
            ]
        );
        $shippingRepRole = Role::findOrCreate('shipping_representative');
    }
}
