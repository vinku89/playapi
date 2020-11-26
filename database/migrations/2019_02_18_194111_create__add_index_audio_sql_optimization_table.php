<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddIndexAudioSqlOptimizationTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('audios', function(Blueprint $table){
            $table->index('id');
            $table->index('slug');
        });
        Schema::table('audio_albums', function(Blueprint $table){
            $table->index('id');
            $table->index('slug');
        });
        Schema::table('audio_artists', function(Blueprint $table){
            $table->index('id');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('audios', function(Blueprint $table){
            $table->dropIndex('id');
            $table->dropIndex('slug');
        });
        Schema::table('audio_albums', function(Blueprint $table){
            $table->dropIndex('id');
            $table->dropIndex('slug');
        });
        Schema::table('audio_artists', function(Blueprint $table){
            $table->dropIndex('id');
            $table->dropIndex('slug');
        });
    }
}
