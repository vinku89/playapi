<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_metadata', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('video_id')->unsigned();
            $table->string('custom_url')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('keyword')->nullable();
            $table->bigInteger('creator_id')->default(0);
            $table->bigInteger('updator_id')->default(0);
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('video_metadata');
    }
}
