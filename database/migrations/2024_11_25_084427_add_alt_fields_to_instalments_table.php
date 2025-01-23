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
            $table->string('cover_photo_alt', 255)->nullable()->after('cover_photo_path');
            $table->string('banner_alt', 255)->nullable()->after('banner_path');
            $table->string('tonight_banner_alt', 255)->nullable()->after('mobile_tonight_banner_photo_path');
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
            $table->dropColumn(['cover_photo_alt', 'banner_alt', 'tonight_banner_alt']);
        });
    }
};
