<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactAndEmailToSuppliersTable extends Migration
{
    public function up()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'contact')) {
                $table->string('contact')->nullable();
            }
            if (!Schema::hasColumn('suppliers', 'email')) {
                $table->string('email')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'contact')) {
                $table->dropColumn('contact');
            }
            if (Schema::hasColumn('suppliers', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
} 