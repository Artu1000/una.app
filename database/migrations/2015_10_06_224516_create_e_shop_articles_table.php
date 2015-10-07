<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEShopArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_shop_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->integer('category_id')->index();
            $table->integer('order_type_id')->index();
            $table->string('available_sizes')->nullable();
            $table->double('price')->nullable();
            $table->string('photo_1')->nullable();
            $table->string('photo_2')->nullable();
            $table->string('photo_3')->nullable();
            $table->timestamps();

            $table->string('description')->nullable();
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
        Schema::drop('e_shop_articles');
    }
}
