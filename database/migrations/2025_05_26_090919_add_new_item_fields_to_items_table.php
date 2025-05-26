<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewItemFieldsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Item Details
            if (!Schema::hasColumn('items', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('items', 'specifications')) {
                $table->json('specifications')->nullable();
            }
            if (!Schema::hasColumn('items', 'asset_type')) {
                $table->enum('asset_type', ['fixed', 'current'])->default('fixed');
            }
            if (!Schema::hasColumn('items', 'value')) {
                $table->decimal('value', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('items', 'depreciation_cost')) {
                $table->decimal('depreciation_cost', 10, 2)->nullable();
            }
            
            // Purchase Information
            if (!Schema::hasColumn('items', 'purchased_by')) {
                $table->unsignedBigInteger('purchased_by')->nullable();
            }
            if (!Schema::hasColumn('items', 'supplier_id')) {
                $table->unsignedBigInteger('supplier_id')->nullable();
            }
            if (!Schema::hasColumn('items', 'purchase_date')) {
                $table->date('purchase_date')->nullable();
            }
            if (!Schema::hasColumn('items', 'received_by')) {
                $table->unsignedBigInteger('received_by')->nullable();
            }
            
            // Status and Remarks
            if (!Schema::hasColumn('items', 'status')) {
                $table->enum('status', ['available', 'in_use', 'maintenance', 'not_traceable', 'disposed'])->default('available');
            }
            if (!Schema::hasColumn('items', 'remarks')) {
                $table->text('remarks')->nullable();
            }
            
            // Location Information
            if (!Schema::hasColumn('items', 'floor_level')) {
                $table->string('floor_level')->nullable();
            }
            if (!Schema::hasColumn('items', 'room_number')) {
                $table->string('room_number')->nullable();
            }
            
            // Approval
            if (!Schema::hasColumn('items', 'is_approved')) {
                $table->boolean('is_approved')->default(false);
            }
            if (!Schema::hasColumn('items', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            }
            if (!Schema::hasColumn('items', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
        });

        // Add foreign keys after all columns are added
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'purchased_by')) {
                $table->foreign('purchased_by')->references('id')->on('users')->onDelete('set null');
            }
            if (Schema::hasColumn('items', 'received_by')) {
                $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
            }
            if (Schema::hasColumn('items', 'approved_by')) {
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
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
            }
            if (Schema::hasColumn('items', 'received_by')) {
                $table->dropForeign(['received_by']);
            }
            if (Schema::hasColumn('items', 'approved_by')) {
                $table->dropForeign(['approved_by']);
            }
            $columns = [
                'description',
                'specifications',
                'asset_type',
                'value',
                'depreciation_cost',
                'purchased_by',
                'supplier_id',
                'purchase_date',
                'received_by',
                'remarks',
                'floor_level',
                'room_number',
                'is_approved',
                'approved_by',
                'approved_at'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
