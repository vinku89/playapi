<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('audio_title');
            $table->string('slug');
            $table->integer('album_id')->nullable();
            $table->string('ad_audio_url');
            $table->string('hls_playlist_url');
            $table->integer('audio_artist_id');
            $table->string('audio_thumbnail');
            $table->mediumText('audio_description')->nullable();
            $table->char('audio_duration')->default('0:00');
            $table->string('pipeline_id');
            $table->string('job_id');
            $table->string('job_status');
            $table->string('fine_uploader_uuid');
            $table->string('fine_uploader_name');
            $table->integer('play_count');
            $table->string('transcoding_percentage', 4);
            $table->tinyInteger('is_active')->default(0);
            $table->bigInteger('creator_id')->default(0);
            $table->bigInteger('updator_id')->default(0);
            $table->tinyInteger('is_archived')->default(0);
            $table->timestamp('archived_on');
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
        Schema::dropIfExists('audios');
    }
}
