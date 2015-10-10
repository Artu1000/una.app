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
            $table->integer('category_id')->index();
            $table->integer('availability_type_id')->index();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->boolean('size_s')->default(false);
            $table->boolean('size_m')->default(false);
            $table->boolean('size_l')->default(false);
            $table->boolean('size_xl')->default(false);
            $table->boolean('size_xxl')->default(false);
            $table->double('price')->nullable();
            $table->string('photo')->nullable();
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
        Schema::drop('e_shop_articles');
    }
}
