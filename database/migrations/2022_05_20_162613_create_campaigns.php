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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title')->unique();
            $table->string('slogan')->nullable();
            $table->string('slug', 128)->unique();
            $table->json('content')->nullable();
            $table->string('facebook_link_url')->nullable();

            $table->string('banner_path')->nullable();

            $table->integer('carousel_id')->unsigned()->nullable();
            $table->foreign('carousel_id')->references('id')->on('carousels')->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('campaigns');
    }
};
