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
        Schema::table('blogs', function (Blueprint $table) {
            Schema::create('blogs', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('title')->unique();
                $table->string('slogan')->nullable();
                $table->string('slug', 128)->unique();
                $table->integer('blog_type_id')->unsigned();
                $table->json('content')->nullable();
                $table->string('facebook_link_url')->nullable();
                $table->string('cover_photo_path')->nullable();
                $table->string('banner_path')->nullable();
                //$table->integer('is_archived')->unsigned()->default(0);
                $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            Schema::dropIfExists('blogs');
        });
    }
};
