<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('subtitle');
            $table->text('subtitle_path');
            $table->string('slug');
            $table->text('video_url');
            $table->text('description');
            $table->text('short_description');
            $table->text('preview_image');
            $table->text('thumbnail_image');
            $table->text('thumbnail_path');
            $table->char('video_duration')->default('0:00');
            $table->boolean('is_hls');
            $table->text('hls_playlist_url');
            $table->text('aws_prefix');
            $table->text('selected_thumb');
            $table->boolean('youtube_live');
            $table->string('youtube_id');
            $table->string('scheduledStartTime');
            $table->string('nextPageToken');
            $table->string('totalResults');
            $table->text('disclaimer');
            $table->integer('is_feature_time');
            $table->bigInteger('country_id');
            $table->string('fine_uploader_uuid');
            $table->string('fine_uploader_name');
            $table->text('subscription');
            $table->string('pipeline_id');
            $table->string('job_id');
            $table->string('job_status');
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_subscription')->default(0);
            $table->tinyInteger('trailer_status')->default(0);
            $table->date('published_on')->nullable();
            $table->integer('video_order')->default(0);
            $table->string('liveStatus');
            $table->string('youtubePrivacy');
            $table->string('presenter');
            $table->string('pdf');
            $table->string('word');
            $table->string('mp3');
            $table->string('broadcast_location')->nullable();
            $table->string('stream_id')->nullable();
            $table->string('source_url')->nullable();
            $table->string('encoder_type')->nullable();
            $table->string('hosted_page_url')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('stream_name')->nullable();
            $table->tinyInteger('notification_status')->default(0);
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
        Schema::drop('videos');
    }
}
