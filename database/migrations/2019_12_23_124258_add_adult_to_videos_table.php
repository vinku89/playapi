<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdultToVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('is_adult')->default(1)->after('price');
            $table->integer('epg_channel_id')->nullable()->after('is_adult');
            $table->integer('custom_sid')->nullable()->after('epg_channel_id');
            $table->integer('tv_archive')->nullable()->after('custom_sid');
            $table->string('direct_source')->nullable()->after('tv_archive');
            $table->string('tv_archive_duration')->nullable()->after('direct_source');
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
            $table->dropColumn('is_adult');
            $table->dropColumn('epg_channel_id');
            $table->dropColumn('custom_sid');
            $table->dropColumn('tv_archive');
            $table->dropColumn('direct_source');
            $table->dropColumn('tv_archive_duration');
        });
    }
}
