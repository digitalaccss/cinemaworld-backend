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
          $table->boolean('is_show_popup')->default(0);
          $table->boolean('is_popup_open_in_new_tab')->default(1);
          $table->string('popup_type')->nullable();
          $table->string('popup_image_path')->nullable();
          $table->string('popup_video_link')->nullable();
          $table->string('popup_external_link')->nullable();
          $table->string('popup_video_button_text', 50)->nullable();
          $table->string('popup_video_button_text_colour')->nullable();
          $table->string('popup_video_button_colour')->nullable();
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
          $table->dropColumn('is_show_popup');
          $table->dropColumn('is_popup_open_in_new_tab');
          $table->dropColumn('popup_type');
          $table->dropColumn('popup_image_path');
          $table->dropColumn('popup_video_link');
          $table->dropColumn('popup_external_link');
          $table->dropColumn('popup_video_button_text');
          $table->dropColumn('popup_video_button_text_colour');
          $table->dropColumn('popup_video_button_colour');
        });
    }
};
