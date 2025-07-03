<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'depreciation_method')) {
                $table->string('depreciation_method')->nullable()->after('room_number');
            }
            if (!Schema::hasColumn('items', 'depreciation_rate')) {
                $table->decimal('depreciation_rate', 8, 2)->nullable()->after('depreciation_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'depreciation_method')) {
                $table->dropColumn('depreciation_method');
            }
            if (Schema::hasColumn('items', 'depreciation_rate')) {
                $table->dropColumn('depreciation_rate');
            }
        });
    }
};
