<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Add new ENUM column
        Schema::table('items', function (Blueprint $table) {
            $table->enum('status_new', ['available', 'in_use', 'maintenance', 'not_traceable', 'disposed'])->default('available')->after('status');
        });

        // 2. Copy data from old status to new status_new (if possible)
        // If old status is 1, set to 'available', else set to 'in_use' (customize as needed)
        DB::table('items')->update([
            'status_new' => DB::raw("CASE WHEN status = 1 THEN 'available' ELSE 'in_use' END")
        ]);

        // 3. Drop old status column
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // 4. Rename new column to status
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 1. Add back old status as boolean
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('status')->default(1)->after('status');
        });

        // 2. Copy data back (if status = 'available', set to 1, else 0)
        DB::table('items')->update([
            'status' => DB::raw("CASE WHEN status = 'available' THEN 1 ELSE 0 END")
        ]);

        // 3. Drop ENUM status column
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
