<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    protected $signature = 'admin:create-super {--email=} {--password=} {--name=}';
    protected $description = 'Create a super admin user with secure credentials';

    public function handle()
    {
        // Check if super admin already exists
        $existingSuperAdmin = User::where('role', 'super_admin')->first();
        
        if ($existingSuperAdmin) {
            $this->error('Super Admin already exists!');
            $this->info('Email: ' . $existingSuperAdmin->email);
            return 1;
        }

        // Get credentials from options or prompt
        $email = $this->option('email') ?: $this->ask('Enter Super Admin email');
        $password = $this->option('password') ?: $this->secret('Enter Super Admin password');
        $name = $this->option('name') ?: $this->ask('Enter Super Admin name', 'Super Admin');

        // Validate input
        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
            'name' => $name,
        ], [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Create super admin
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'is_admin' => true,
        ]);

        $this->info('Super Admin created successfully!');
        $this->info('Email: ' . $email);
        $this->info('Name: ' . $name);
        $this->warn('Please save these credentials securely!');
        
        return 0;
    }
} 