<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_user_id')->nullable()->constrained('users');
            $table->foreignId('to_user_id')->nullable()->constrained('users');
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->string('movement_type'); // assignment, transfer, return, maintenance
            $table->text('notes')->nullable();
            $table->foreignId('moved_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_movements');
    }
}
