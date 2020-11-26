<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryCollectionsVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections_videos', function (Blueprint $table) {
            $table->bigInteger ( 'parent_cateogry_id' )->unsigned ()->after('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections_videos', function (Blueprint $table) {
            $table->dropColumn('parent_cateogry_id');
        });
    }
}
