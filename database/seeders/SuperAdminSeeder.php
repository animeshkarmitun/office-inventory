<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if super admin already exists
        $existingSuperAdmin = User::where('role', 'super_admin')->first();
        
        if (!$existingSuperAdmin) {
            User::create([
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                'email' => env('SUPER_ADMIN_EMAIL', 'superadmin@office-inventory.com'),
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'superadmin123')),
                'role' => 'super_admin',
                'is_admin' => true,
            ]);
            
            $this->command->info('Super Admin created successfully!');
            $this->command->info('Email: ' . env('SUPER_ADMIN_EMAIL', 'superadmin@office-inventory.com'));
            $this->command->info('Password: ' . env('SUPER_ADMIN_PASSWORD', 'superadmin123'));
        } else {
            $this->command->info('Super Admin already exists!');
        }
    }
} 