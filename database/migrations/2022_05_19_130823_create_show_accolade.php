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
        Schema::create('show_accolade', function (Blueprint $table) {
            $table->integer('show_id')->unsigned();
            $table->integer('accolade_id')->unsigned();

            $table->foreign('show_id')->references('id')->on('shows')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('accolade_id')->references('id')->on('accolades')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('show_accolade');
    }
};
