<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'role' => 'admin',
        ]);

        // Create asset manager user
        User::create([
            'name' => 'Asset Manager',
            'email' => 'assetmanager@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'role' => 'asset_manager',
        ]);

        // Create regular employee user
        User::create([
            'name' => 'Regular Employee',
            'email' => 'employee@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'role' => 'employee',
        ]);
    }
} 