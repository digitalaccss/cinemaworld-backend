<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscribepartners', function (Blueprint $table) {
            $table->string('logo_path', 255)->nullable()->after('streaming');
        });
    }

    public function down()
    {
        Schema::table('subscribepartners', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });
    }
};
