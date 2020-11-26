<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnTypeXRayCastTable extends Migration
{
      /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('x_ray_cast', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
            $table->string('banner_image')->nullable()->change();
            $table->string('external_url')->nullable()->change();
            $table->string('is_active')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('x_ray_cast', function (Blueprint $table) {
            $table->string('description')->default('')->change();
            $table->string('banner_image')->default('')->change();
            $table->string('external_url')->default('')->change();
            $table->string('is_active')->default('')->change();
        });
    }
}
