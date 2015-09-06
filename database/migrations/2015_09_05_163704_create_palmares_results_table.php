<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalmaresResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palmares_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('palmares_event_id')->unsigned();
            $table->foreign('palmares_event_id')->references('id')->on('palmares_events')->onDelete('cascade');
            $table->string('boat');
            $table->integer('position');
            $table->string('crew');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('palmares_results');
    }
}
