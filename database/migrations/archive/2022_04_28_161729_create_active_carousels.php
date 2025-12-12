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
        Schema::create('active_carousels', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->integer('carousel_id')->unsigned();
            $table->foreign('carousel_id')->references('id')->on('carousels')->onDelete('cascade');

            $table->json('films')->nullable();

            $table->integer('order_column')->unsigned();

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
        Schema::dropIfExists('active_carousels');
    }
};
