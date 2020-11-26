<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoAudioFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_audio_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_id');
            $table->string('audio_title');
            $table->text('audio_src_url');
            $table->text('audio_hls_url');
            $table->text('audio_hls_prefix');
            $table->string('pipeline_id');
            $table->string('job_id');
            $table->string('job_status');
            $table->tinyInteger('Video_hls_update_status')->default(0);
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
        Schema::dropIfExists('video_audio_uploads');
    }
}
