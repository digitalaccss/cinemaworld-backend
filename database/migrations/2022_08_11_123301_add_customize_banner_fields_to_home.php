<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('home', function (Blueprint $table) {
            $table->string('customize_banner_display_text', 80);
            $table->string('customize_banner_button_text', 30);
            $table->string('customize_banner_button_link_url');
            $table->string('customize_banner_background_colour', 10);
            $table->string('customize_banner_button_colour', 10);
            $table->string('customize_banner_image_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home', function (Blueprint $table) {
            $table->dropColumn('customize_banner_display_text');
            $table->dropColumn('customize_banner_button_text');
            $table->dropColumn('customize_banner_button_link_url');
            $table->dropColumn('customize_banner_background_colour');
            $table->dropColumn('customize_banner_button_colour');
            $table->dropColumn('customize_banner_image_path');
        });
    }
};
