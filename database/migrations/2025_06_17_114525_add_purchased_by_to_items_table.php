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
            if (!Schema::hasColumn('items', 'purchased_by')) {
                $table->unsignedBigInteger('purchased_by')->nullable()->after('purchase_date');
                $table->foreign('purchased_by')->references('id')->on('users')->onDelete('set null');
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
            if (Schema::hasColumn('items', 'purchased_by')) {
                $table->dropForeign(['purchased_by']);
                $table->dropColumn('purchased_by');
            }
        });
    }
};
