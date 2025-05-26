<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLocationColumnTypeInDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('location')->change();
        });
    }

    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->integer('location')->change();
        });
    }
} 