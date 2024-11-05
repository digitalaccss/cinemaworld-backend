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
        Schema::table('shows', function (Blueprint $table) {
            $table->string('director_statement_alt')->nullable()->after('director_statement');
            $table->string('tonight_banner_alt')->nullable()->after('mobile_tonight_banner_photo_path');
            $table->string('banner_alt')->nullable()->after('banner_path');
            $table->string('image_alt')->nullable()->after('cover_photo_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropColumn('director_statement_alt');
            $table->dropColumn('tonight_banner_alt');
            $table->dropColumn('banner_alt');
            $table->dropColumn('image_alt');
        });
    }
};
