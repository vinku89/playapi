<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveExtraFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
          
            if (Schema::hasColumn('videos', 'subtitle_path')) {
                $table->dropColumn('subtitle_path');
            }
            if (Schema::hasColumn('videos', 'short_description')) {
                $table->dropColumn('short_description');
            }
            if (Schema::hasColumn('videos', 'preview_image')) {
                $table->dropColumn('preview_image');
            }
            if (Schema::hasColumn('videos', 'thumbnail_path')) {
                $table->dropColumn('thumbnail_path');
            }
            if (Schema::hasColumn('videos', 'selected_thumb')) {
                $table->dropColumn('selected_thumb');
            }
            if (Schema::hasColumn('videos', 'youtube_id')) {
                $table->dropColumn('youtube_id');
            }
            if (Schema::hasColumn('videos', 'nextPageToken')) {
                $table->dropColumn('nextPageToken');
            }
            if (Schema::hasColumn('videos', 'totalResults')) {
                $table->dropColumn('totalResults');
            }
            if (Schema::hasColumn('videos', 'disclaimer')) {
                $table->dropColumn('disclaimer');
            }
            if (Schema::hasColumn('videos', 'is_feature_time')) {
                $table->dropColumn('is_feature_time');
            }
            if (Schema::hasColumn('videos', 'country_id')) {
                $table->dropColumn('country_id');
            }
            if (Schema::hasColumn('videos', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
            if (Schema::hasColumn('videos', 'trailer_status')) {
                $table->dropColumn('trailer_status');
            }
            if (Schema::hasColumn('videos', 'word')) {
                $table->dropColumn('word');
            }
            if (Schema::hasColumn('videos', 'youtubePrivacy')) {
                $table->dropColumn('youtubePrivacy');
            }
            if (Schema::hasColumn('videos', 'pdf')) {
                $table->dropColumn('pdf');
            }
            if (Schema::hasColumn('videos', 'mp3')) {
                $table->dropColumn('mp3');
            }
            if (Schema::hasColumn('videos', 'hosted_page_url')) {
                $table->dropColumn('hosted_page_url');
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
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'subtitle_path')) {
                $table->text('subtitle_path');
            }
            if (!Schema::hasColumn('videos', 'short_description')) {
                $table->text('short_description');
            }
            if (!Schema::hasColumn('videos', 'preview_image')) {
                $table->text('preview_image');
            }
            if (!Schema::hasColumn('videos', 'thumbnail_path')) {
                $table->text('thumbnail_path');
            }
            if (!Schema::hasColumn('videos', 'selected_thumb')) {
                $table->text('selected_thumb');
            }
            if (!Schema::hasColumn('videos', 'youtube_id')) {
                $table->string('youtube_id');
            }
            if (!Schema::hasColumn('videos', 'nextPageToken')) {
                $table->string('nextPageToken');
            }
            if (!Schema::hasColumn('videos', 'totalResults')) {
                $table->string('totalResults');
            }
            if (!Schema::hasColumn('videos', 'disclaimer')) {
                $table->string('disclaimer');
            }
            if (!Schema::hasColumn('videos', 'is_feature_time')) {
                $table->integer('is_feature_time');
            }
            if (!Schema::hasColumn('videos', 'country_id')) {
                $table->bigInteger('country_id');
            }
            if (!Schema::hasColumn('videos', 'is_featured')) {
                $table->tinyInteger('is_featured')->default(0);
            }
            if (!Schema::hasColumn('videos', 'trailer_status')) {
                $table->tinyInteger('trailer_status')->default(0);
            }
            if (!Schema::hasColumn('videos', 'word')) {
                $table->string('word');
            }
            if (!Schema::hasColumn('videos', 'youtubePrivacy')) {
                $table->string('youtubePrivacy');
            }
            if (!Schema::hasColumn('videos', 'pdf')) {
                $table->string('pdf');
            }
            if (!Schema::hasColumn('videos', 'mp3')) {
                $table->string('mp3');
            }
            if (!Schema::hasColumn('videos', 'hosted_page_url')) {
                $table->string('hosted_page_url')->nullable();
            }
        });
    }
}
