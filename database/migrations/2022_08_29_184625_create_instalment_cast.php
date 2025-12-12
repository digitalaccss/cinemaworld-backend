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
        Schema::create('instalment_cast', function (Blueprint $table) {
            $table->integer('instalment_id')->unsigned();
            $table->integer('cast_id')->unsigned();

            $table->foreign('instalment_id')->references('id')->on('instalments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('cast_id')->references('id')->on('cast')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('instalment_cast');
    }
};
