<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->bigInteger('parent_id')->default(0);
            $table->integer('category_order')->default(0);
            $table->integer('preference_order')->nullable();
            $table->string('level');
            $table->text('image_url');
            $table->text('image_path');
            $table->tinyInteger('is_deletable')->default(1);
            $table->bigInteger('is_leaf_category')->default(0);
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
        Schema::drop('categories');
    }
}
