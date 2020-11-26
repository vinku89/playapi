<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('album_name', 255);
            $table->string('slug');
            $table->integer('album_artist_id');
            $table->integer('genre_id')->nullable(true);
            $table->mediumText('album_description')->nullable();
            $table->string('album_thumbnail', 255);
            $table->integer('audio_language_category_id');
            $table->integer('play_count');
            $table->date('album_release_date', 255);
            $table->tinyInteger('is_notify_customer')->default(0);
            $table->integer('is_active');
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
        Schema::dropIfExists('audio_albums');
    }
}
