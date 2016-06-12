<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->integer('photo_album_id')->after('category_id')->unsigned()->nullable();
            $table->foreign('photo_album_id')->references('id')->on('photos')->onDelete('cascade');
            $table->integer('video_id')->after('photo_album_id')->unsigned()->nullable();
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign('news_photo_album_id_foreign');
            $table->dropColumn('photo_album_id');
            $table->dropForeign('news_video_id_foreign');
            $table->dropColumn('video_id');
        });
    }
}
