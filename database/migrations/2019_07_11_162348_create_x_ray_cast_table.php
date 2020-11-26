<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXRayCastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('x_ray_cast', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('description');
            $table->longText('banner_image');
            $table->longText('external_url');
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('x_ray_cast');
    }
}
