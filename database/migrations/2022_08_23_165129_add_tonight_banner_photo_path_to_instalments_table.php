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
        Schema::table('instalments', function (Blueprint $table) {
            $table->string('web_tonight_banner_photo_path')->nullable();
            $table->string('mobile_tonight_banner_photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instalments', function (Blueprint $table) {
            $table->dropColumn('web_tonight_banner_photo_path');
            $table->dropColumn('mobile_tonight_banner_photo_path');
        });
    }
};
