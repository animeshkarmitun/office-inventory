<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailColumnToBorrowersTable extends Migration
{
    public function up()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowers', 'email')) {
                $table->string('email')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('borrowers', function (Blueprint $table) {
            if (Schema::hasColumn('borrowers', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
} 