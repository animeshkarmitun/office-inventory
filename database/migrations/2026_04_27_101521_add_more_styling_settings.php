<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreStylingSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $settings = [
            ['key' => 'app_bg_color', 'value' => '#f8f9fa', 'type' => 'color', 'group' => 'styling'],
            ['key' => 'link_color', 'value' => '#5e72e4', 'type' => 'color', 'group' => 'styling'],
            ['key' => 'body_text_color', 'value' => '#525f7f', 'type' => 'color', 'group' => 'styling'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

    public function down()
    {
        \App\Models\Setting::whereIn('key', ['app_bg_color', 'link_color', 'body_text_color'])->delete();
    }
}
