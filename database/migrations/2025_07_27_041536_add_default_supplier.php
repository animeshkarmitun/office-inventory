<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create default supplier if it doesn't exist
        if (!\App\Models\Supplier::where('name', 'Default Supplier')->exists()) {
            \App\Models\Supplier::create([
                'name' => 'Default Supplier',
                'incharge_name' => 'System Default',
                'contact_number' => 'N/A',
                'email' => 'default@system.com',
                'address' => 'System Generated',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove default supplier
        \App\Models\Supplier::where('name', 'Default Supplier')->delete();
    }
}
