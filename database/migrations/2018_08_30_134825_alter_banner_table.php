<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->bigInteger('video_id')->after('video_image')->nullable(true);

            
            if (Schema::hasColumn('banners', 'title')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `title` `title` VARCHAR(255) NULL;');
            }

            if (Schema::hasColumn('banners', 'url')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `url` `url` text NULL;');
            }
            if (Schema::hasColumn('banners', 'type')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `type` `type` VARCHAR(255) NULL;');
            }
            if (Schema::hasColumn('banners', 'extension')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `extension` `extension` VARCHAR(255) NULL;');
            }
            if (Schema::hasColumn('banners', 'video_image')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `video_image` `video_image` text NULL;');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('video_id');

            if (Schema::hasColumn('banners', 'title')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `title` `title` VARCHAR(255) NOT NULL;');
            }
            if (Schema::hasColumn('banners', 'url')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `url` `url` text NOT NULL;');
            }
            if (Schema::hasColumn('banners', 'type')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `type` `type` VARCHAR(255) NOT NULL;');
            }
            if (Schema::hasColumn('banners', 'extension')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `extension` `extension` VARCHAR(255) NOT NULL;');
            }
            if (Schema::hasColumn('banners', 'video_image')) {
                DB::statement('ALTER TABLE `banners` CHANGE COLUMN `video_image` `video_image` text NOT NULL;');
            }
        });
    }
}
