<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudioAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_ads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ad_name', 255);
            $table->string('ad_slug', 255);
            $table->string('audio_ad_audio_url', 255)->nullable();
            $table->string('ad_image')->nullable();
            $table->string('ad_url')->nullable();
            $table->char('ad_audio_duration')->default('0:00');
            $table->string('audio_ad_pipeline_id');
            $table->string('audio_ad_job_id');
            $table->string('audio_ad_job_status');
            $table->string('audio_ad_fine_uploader_uuid');
            $table->string('audio_ad_fine_uploader_name');
            $table->integer('audio_ad_play_count')->default(0);
            $table->string('audio_ad_transcoding_percentage', 4);
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
        Schema::dropIfExists('audio_ads');
    }
}
