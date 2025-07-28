<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddSuperAdminRoleToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update the role enum to include super_admin using raw SQL
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'asset_manager', 'employee') DEFAULT 'employee'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the role enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'asset_manager', 'employee') DEFAULT 'employee'");
    }
}
