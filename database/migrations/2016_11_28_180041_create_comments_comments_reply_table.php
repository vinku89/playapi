<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsCommentsReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('video_id')->unsigned();
            $table->string('user_type');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->text('comment');
            $table->integer('is_active')->default(0);
            $table->integer('is_read')->default(0);
            $table->integer('is_notify')->default(0);
            $table->bigInteger('creator_id')->default(0);
            $table->bigInteger('updator_id')->default(0);
            $table->timestamps();
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
        Schema::drop('comments');
    }
}
