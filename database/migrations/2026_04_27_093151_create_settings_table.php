<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $row) {
            $row->id();
            $row->string('key')->unique();
            $row->text('value')->nullable();
            $row->string('type')->default('text'); // text, color, file, etc.
            $row->string('group')->default('general'); // general, branding, styling
            $row->timestamps();
        });

        // Insert default settings
        $defaults = [
            ['key' => 'app_title', 'value' => 'Office Inventory', 'type' => 'text', 'group' => 'general'],
            ['key' => 'app_logo', 'value' => null, 'type' => 'file', 'group' => 'branding'],
            ['key' => 'app_favicon', 'value' => null, 'type' => 'file', 'group' => 'branding'],
            
            // Sidebar styling
            ['key' => 'sidebar_bg_color', 'value' => '#212529', 'type' => 'color', 'group' => 'styling'],
            ['key' => 'sidebar_text_color', 'value' => '#ffffff', 'type' => 'color', 'group' => 'styling'],
            ['key' => 'sidebar_text_size', 'value' => '1rem', 'type' => 'text', 'group' => 'styling'],
            
            // Table styling
            ['key' => 'table_header_bg', 'value' => '#f8f9fa', 'type' => 'color', 'group' => 'styling'],
            ['key' => 'table_text_size', 'value' => '0.875rem', 'type' => 'text', 'group' => 'styling'],
            
            // Button styling
            ['key' => 'btn_primary_bg', 'value' => '#0d6efd', 'type' => 'color', 'group' => 'styling'],
            ['key' => 'btn_primary_color', 'value' => '#ffffff', 'type' => 'color', 'group' => 'styling'],
            
            // Header/Title styling
            ['key' => 'header_text_color', 'value' => '#212529', 'type' => 'color', 'group' => 'styling'],
            ['key' => 'header_text_size', 'value' => '1.75rem', 'type' => 'text', 'group' => 'styling'],
        ];

        foreach ($defaults as $default) {
            \App\Models\Setting::create($default);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
