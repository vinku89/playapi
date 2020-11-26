<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePresetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_presets', function (Blueprint $table) {
            $table->string('preset_max_height',10)->after('thumbnail_format')->nullable();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string('video_height',10)->after('video_duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_presets', function (Blueprint $table) {
            $table->dropColumn('preset_max_height');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('video_height',10);
        });
    }
}
