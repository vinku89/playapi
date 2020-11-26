<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoWebseriesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_webseries_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('poster_image')->nullable();
            $table->string('starring')->nullable();
            $table->integer('genre_id')->nullable();
            $table->integer('parent_category_id')->nullable();
            $table->tinyInteger('is_active')->default(0);
            $table->bigInteger('creator_id')->default(0);
            $table->bigInteger('updator_id')->default(0);
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
        Schema::dropIfExists('video_webseries_detail');
    }
}
