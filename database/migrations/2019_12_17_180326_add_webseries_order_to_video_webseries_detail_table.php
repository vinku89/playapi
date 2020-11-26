<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWebseriesOrderToVideoWebseriesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_webseries_detail', function (Blueprint $table) {
            $table->integer('webseries_order')->nullable();
            $table->tinyInteger('is_active_home')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_webseries_detail', function (Blueprint $table) {
            $table->dropColumn('is_active_home');
            $table->dropColumn('webseries_order');
        });
    }
}
