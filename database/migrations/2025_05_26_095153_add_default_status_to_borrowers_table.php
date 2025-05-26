<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultStatusToBorrowersTable extends Migration
{
    public function up()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });
    }

    public function down()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
} 