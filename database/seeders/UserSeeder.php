<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create exactly 5 corporate users with different roles
        $users = [
            [
                'name' => 'John Anderson',
                'email' => 'john.anderson@company.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => 'super_admin',
                'department_id' => 1, // IT Department
                'designation_id' => 1 // IT Manager
            ],
            [
                'name' => 'Sarah Mitchell',
                'email' => 'sarah.mitchell@company.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => 'admin',
                'department_id' => 2, // HR Department
                'designation_id' => 6 // HR Manager
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@company.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'asset_manager',
                'department_id' => 3, // Finance Department
                'designation_id' => 11 // Finance Manager
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@company.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'employee',
                'department_id' => 4, // Marketing Department
                'designation_id' => 16 // Marketing Manager
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david.thompson@company.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'employee',
                'department_id' => 5, // Operations Department
                'designation_id' => 21 // Operations Manager
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('Created 5 corporate users successfully!');
    }
} 