<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLiveRecordedStatusVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('live_recorded_status')->after('is_live')->nullable(true);
            $table->integer('live_recording_confirmation')->after('live_recorded_status')->nullable(true);
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
            $table->dropColumn('live_recorded_status');
            $table->dropColumn('live_recording_confirmation');
        });
    }
}