<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVideoPresenter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            DB::statement('ALTER TABLE `videos` CHANGE `presenter` `presenter` TEXT');
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
            DB::statement('ALTER TABLE `videos` CHANGE `presenter` `presenter` VARCHAR(191)');
        });
    }
}
