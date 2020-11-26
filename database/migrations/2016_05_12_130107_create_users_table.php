<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('password', 60);
            $table->string('phone', 50)->nullable();
            $table->string('gender')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('profile_image_path')->nullable();
            $table->bigInteger('parent_id')->default(0);
            $table->integer('user_group_id')->default(0);
            $table->string('access_token')->nullable();
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
