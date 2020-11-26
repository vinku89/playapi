<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_artists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('artist_name', 255);
            $table->string('slug');
            $table->string('artist_thumbnail', 255);
            $table->mediumText('artist_biography')->nullable();
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
        Schema::dropIfExists('audio_artists');
    }
}
