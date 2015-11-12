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
            $table->integer('palmares_event_id')->unsigned()->index();
            $table->foreign('palmares_event_id')->references('id')->on('palmares_events')->onDelete('cascade');
            $table->string('boat')->nullable();
            $table->integer('position')->nullable();
            $table->string('crew')->nullable();

            $table->timestamps();
            $table->engine = 'InnoDB';
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
