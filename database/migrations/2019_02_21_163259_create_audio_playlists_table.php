<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioPlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_playlists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('playlist_name',300);
            $table->string('playlist_slug',300);
            $table->string('playlist_thumbnail',300);
            $table->integer('order')->default(0);
            $table->tinyInteger('is_active');
            $table->integer('creator_id')->default(0);
            $table->integer('updator_id')->default(0);
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
        Schema::dropIfExists('audio_playlists');
    }
}
