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
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->date('schedule_start_date');
            $table->time('schedule_start_time');

            $table->date('schedule_end_date');
            $table->time('schedule_end_time');

            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('shows')->onUpdate('cascade')->onDelete('cascade');

            $table->integer('instalment_id')->unsigned()->nullable();
            $table->foreign('instalment_id')->references('id')->on('instalments')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('schedules');
    }
};
