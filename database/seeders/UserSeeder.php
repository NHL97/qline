<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // QLine platform users
        User::create([
            'business_id' => null,
            'name'        => 'Super Admin',
            'email'       => 'admin@qline.app',
            'password'    => Hash::make('password'),
            'role'        => 'superadmin',
            'is_active'   => true,
        ]);

        User::create([
            'business_id' => null,
            'name'        => 'QLine Staff',
            'email'       => 'staff@qline.app',
            'password'    => Hash::make('password'),
            'role'        => 'qline_staff',
            'is_active'   => true,
        ]);

        // Business owner — business_id assigned after BusinessSeeder runs
        User::create([
            'business_id' => null,
            'name'        => 'Ahmad Owner',
            'email'       => 'owner@warungahmad.com',
            'password'    => Hash::make('password'),
            'role'        => 'business_owner',
            'is_active'   => true,
        ]);

        // Business staff — business_id assigned after BusinessSeeder runs
        User::create([
            'business_id' => null,
            'name'        => 'Siti Staff',
            'email'       => 'staff@warungahmad.com',
            'password'    => Hash::make('password'),
            'role'        => 'business_staff',
            'is_active'   => true,
        ]);
    }
}