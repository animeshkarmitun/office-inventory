<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseDetailsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->date('purchase_date')->nullable();
            $table->date('warranty_end_date')->nullable();
            $table->string('warranty_status')->default('active');
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->text('purchase_notes')->nullable();
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
            $table->dropColumn([
                'purchase_date',
                'warranty_end_date',
                'warranty_status',
                'purchase_price',
                'purchase_notes'
            ]);
        });
    }
}
