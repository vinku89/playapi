<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
    		$table->bigInteger('country_id');
            $table->bigInteger('category_id');
            $table->bigInteger('video_id');
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
        Schema::drop ( 'country_categories' );
    }
}
