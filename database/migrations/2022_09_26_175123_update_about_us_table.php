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
        Schema::table('aboutus', function (Blueprint $table) {
            $table->string('title', 150);
            $table->string('banner_type', 10);
            $table->string('sub_title', 150)->nullable();
            $table->string('video_link_url')->nullable();
            $table->string('about_us_banner_path')->nullable();
            $table->dropColumn('mobile_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aboutus', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('banner_type');
            $table->dropColumn('sub_title');
            $table->dropColumn('video_link_url');
            $table->dropColumn('about_us_banner_path');
        });
    }
};
