<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssetTrackingFieldsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('asset_tag')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->string('rfid_tag')->unique()->nullable();
            $table->string('location')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->string('condition')->default('good');
            $table->text('description')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->decimal('acquisition_cost', 10, 2)->nullable();
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
            $table->dropForeign(['assigned_to']);
            $table->dropColumn([
                'asset_tag',
                'barcode',
                'rfid_tag',
                'location',
                'assigned_to',
                'condition',
                'description',
                'acquisition_date',
                'acquisition_cost'
            ]);
        });
    }
}
