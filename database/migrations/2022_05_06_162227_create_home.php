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
        Schema::create('home', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->integer('featured_show_id')->unsigned();
            $table->foreign('featured_show_id')->references('id')->on('shows')->onUpdate('cascade')->onDelete('cascade');

            // $table->integer('show_type_id')->unsigned()->nullable();
            // $table->foreign('show_type_id')->references('id')->on('show_types')->onUpdate('cascade')->onDelete('cascade');

            $table->string('featured_banner_path')->nullable();
            $table->json('active_carousels')->nullable();
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
        Schema::dropIfExists('home');
    }
};
