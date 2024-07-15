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
        Schema::create('films', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title')->unique();
            $table->string('translated_title')->nullable();
            $table->string('slug', 50)->unique();
            $table->integer('show_type_id')->unsigned();
            $table->json('genres');
            $table->integer('region_id')->unsigned();
            $table->json('countries');
            $table->json('languages');
            $table->integer('release_year')->unsigned()->nullable();
            $table->integer('runtime')->unsigned()->nullable();
            $table->string('short_desc');
            $table->string('full_desc', 512);
            $table->string('trivia_desc', 512)->nullable();
            $table->string('trailer_link_url')->nullable();
            // $table->json('tags')->nullable();
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
        Schema::dropIfExists('films');
    }
};
