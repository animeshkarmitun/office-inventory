<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetSuperAdmin extends Command
{
    protected $signature = 'admin:reset-super {--email=} {--password=} {--name=}';
    protected $description = 'Reset super admin user with new credentials';

    public function handle()
    {
        // Delete existing super admin
        $existingSuperAdmin = User::where('role', 'super_admin')->first();
        
        if ($existingSuperAdmin) {
            $this->info('Removing existing super admin: ' . $existingSuperAdmin->email);
            $existingSuperAdmin->delete();
        }

        // Get new credentials
        $email = $this->option('email') ?: $this->ask('Enter new Super Admin email');
        $password = $this->option('password') ?: $this->secret('Enter new Super Admin password');
        $name = $this->option('name') ?: $this->ask('Enter new Super Admin name', 'Super Admin');

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

        // Create new super admin
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'super_admin',
            'is_admin' => true,
        ]);

        $this->info('Super Admin reset successfully!');
        $this->info('Email: ' . $email);
        $this->info('Name: ' . $name);
        $this->warn('Please save these credentials securely!');
        
        return 0;
    }
} 