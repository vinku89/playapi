<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            DB::statement('ALTER TABLE `videos` CHANGE `youtube_live` `is_live` TINYINT(1) DEFAULT 0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            DB::statement('ALTER TABLE `videos` CHANGE `is_live` `youtube_live` TINYINT(1) DEFAULT 0');
        });
    }
}
