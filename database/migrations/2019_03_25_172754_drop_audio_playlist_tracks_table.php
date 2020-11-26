<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAudioPlaylistTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('audio_playlist_tracks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('audio_playlist_tracks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('audio_id');
            $table->integer('playlist_id');
            $table->timestamps();
        });
    }
}
