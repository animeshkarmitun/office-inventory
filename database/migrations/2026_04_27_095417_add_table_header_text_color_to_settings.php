<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AddTableHeaderTextColorToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Setting::updateOrCreate(
            ['key' => 'table_header_text_color'],
            [
                'value' => '#495057',
                'type' => 'color',
                'group' => 'styling'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::where('key', 'table_header_text_color')->delete();
    }
}
