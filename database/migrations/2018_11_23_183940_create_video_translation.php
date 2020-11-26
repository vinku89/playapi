<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_id');
            $table->integer('language_id');
            $table->string('title', 255);
            $table->text('description');
            $table->string('presenter', 255)->nullable();
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
        Schema::drop('video_translation');
    }
}
