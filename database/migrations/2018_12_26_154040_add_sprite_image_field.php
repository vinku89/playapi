<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpriteImageField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('sprite_image',200)->after('hls_playlist_url')->nullable();
            $table->tinyInteger('sprite_image_status')->after('sprite_image')->comment('0 - Unprocessed, 1 - Processing, 2 - Completed')->nullable();
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
            $table->dropColumn('sprite_image');
            $table->dropColumn('sprite_image_status');
        });
    }
}
