<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsFooterMenuStaticContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            $table->integer('is_footer_menu')->after('content')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            $table->dropColumn('is_footer_menu');
        });
    }
}