<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class PurgeToSuperAdmin extends Command
{
	protected $signature = 'admin:purge-to-super {--email=} {--password=} {--name=} {--force : Run without interactive confirmation}';
	protected $description = 'Purge all application data and keep only a single Super Admin user';

	public function handle()
	{
		if (!$this->option('force')) {
			$this->warn('WARNING: This will irreversibly delete almost all data in the database.');
			$this->warn('Only a single Super Admin account will remain.');
			if (!$this->confirm('Do you want to proceed?', false)) {
				$this->info('Aborted.');
				return 0;
			}
		}

		$email = $this->option('email') ?: env('SUPER_ADMIN_EMAIL', 'superadmin@office-inventory.com');
		$password = $this->option('password') ?: env('SUPER_ADMIN_PASSWORD', 'superadmin123');
		$name = $this->option('name') ?: env('SUPER_ADMIN_NAME', 'Super Admin');

		DB::beginTransaction();
		try {
			// Collect tables to purge (exclude migrations and users)
			$tables = collect(DB::select('SHOW TABLES'))
				->map(function ($row) {
					return array_values((array) $row)[0];
				})
				->filter(function ($table) {
					return $table !== 'migrations' && $table !== 'users';
				})
				->values();

			// Disable foreign key checks for truncation
			DB::statement('SET FOREIGN_KEY_CHECKS=0');
			foreach ($tables as $table) {
				DB::table($table)->truncate();
			}
			DB::statement('SET FOREIGN_KEY_CHECKS=1');

			// Remove all users except super_admin
			DB::table('users')->where('role', '!=', 'super_admin')->delete();

			// Ensure exactly one super_admin user exists with provided credentials
			$existingSupers = User::where('role', 'super_admin')->get();
			if ($existingSupers->count() > 1) {
				User::where('role', 'super_admin')->where('email', '!=', $email)->delete();
			}

			$super = User::where('role', 'super_admin')->first();
			if (!$super) {
				$super = User::create([
					'name' => $name,
					'email' => $email,
					'password' => Hash::make($password),
					'role' => 'super_admin',
					'is_admin' => true,
				]);
			} else {
				$super->name = $name;
				$super->email = $email;
				$super->password = Hash::make($password);
				$super->is_admin = true;
				$super->save();
			}

			DB::commit();
			$this->info('Purge completed. Only the Super Admin account remains.');
			$this->info('Email: ' . $email);
			$this->info('Name: ' . $name);
			$this->warn('Please save these credentials securely.');
			return 0;
		} catch (\Throwable $e) {
			DB::rollBack();
			$this->error('Purge failed: ' . $e->getMessage());
			return 1;
		}
	}
}
