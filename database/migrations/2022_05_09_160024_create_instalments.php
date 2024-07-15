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
        Schema::create('instalments', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title')->unique();
            $table->string('translated_title')->nullable();
            $table->string('slug', 50)->unique();
            $table->integer('instalment_number')->unsigned();
            $table->integer('release_year')->unsigned();
            $table->integer('runtime')->unsigned();
            $table->string('short_desc');
            $table->string('full_desc', 512);
            $table->string('trivia_desc', 1024)->nullable();
            $table->string('trailer_link_url')->nullable();
            $table->string('cover_photo_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('leaderboard_banner_path')->nullable();

            $table->integer('series_id')->unsigned();
            $table->foreign('series_id')->references('id')->on('shows')->onUpdate('cascade')->onDelete('cascade');

            // $table->timestamps();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instalments');
    }
};
