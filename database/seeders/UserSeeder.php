<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Barbershop;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $ownerRole      = Role::where('name', 'owner')->first();
        $barberRole     = Role::where('name', 'barber')->first();
        $customerRole   = Role::where('name', 'customer')->first();

        // Safety check
        if (!$superAdminRole || !$ownerRole || !$barberRole || !$customerRole) {
            $this->command->error('Roles belum ada. Jalankan RoleSeeder dulu.');
            return;
        }

        // Create 1 barbershop
        $barbershop = Barbershop::firstOrCreate(
            ['name' => 'Little Star Barbershop'],
            [
                'slug' => 'little-star-barbershop',
                'address' => 'Demo Address',
                'city' => 'Yogyakarta',
                'phone' => '08123456789',
                'status' => 'active'
            ]
        );

        // SUPER ADMIN
        User::updateOrCreate(
            ['email' => 'studiolittlestar2@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Password'),
                'role_id' => $superAdminRole->id,
                'barbershop_id' => null,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // OWNER
        User::updateOrCreate(
            ['email' => 'owner@demo.com'],
            [
                'name' => 'Owner Demo',
                'password' => Hash::make('Password'),
                'role_id' => $ownerRole->id,
                'barbershop_id' => $barbershop->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // BARBER
        User::updateOrCreate(
            ['email' => 'barber@demo.com'],
            [
                'name' => 'Barber Demo',
                'password' => Hash::make('Password'),
                'role_id' => $barberRole->id,
                'barbershop_id' => $barbershop->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // CUSTOMERS -  DUMMY CUSTOMERS (50 USERS)
        for ($i = 1; $i <= 50; $i++) {
            User::updateOrCreate(
                ['email' => "customer$i@demo.com"],
                [
                    'name' => "Customer $i",
                    'password' => Hash::make('Password'),
                    'role_id' => $customerRole->id,
                    'barbershop_id' => null,
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
        }

    }
}
