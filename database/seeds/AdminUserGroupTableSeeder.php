<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Contus\User\Models\UserGroup;

class AdminUserGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('user_groups')->count();
        if($existCount <= 0) {
            DB::table('user_groups')->delete();
            DB::unprepared("ALTER TABLE user_groups AUTO_INCREMENT = 1;");
            UserGroup::insert([
                    [
                        'name' => 'SuperAdmin',
                        'permissions' => '{"dashboard_all":1,"video_management":1,"videos_all":1,"ads_all":1,"videos_all_write":1,"videos_all_read":1,"category_all":1,"category_all_write":1,"category_all_read":1,"genre_all":1,"genre_all_write":1,"genre_all_read":1,"season_all":1,"season_all_write":1,"season_all_read":1,"preset_all":1,"preset_all_write":1,"preset_all_read":1,"audio_management":1,"albums_all":1,"albums_all_write":1,"albums_all_read":1,"audios_all":1,"audios_all_write":1,"audios_all_read":1,"artists_all":1,"artists_all_write":1,"artists_all_read":1,"languages_all":1,"languages_all_write":1,"languages_all_read":1,"user_management":1,"customer_all":1,"customer_all_write":1,"customer_all_read":1,"user_all":1,"user_all_write":1,"user_all_read":1,"usergroup_all":1,"usergroup_all_write":1,"usergroup_all_read":1,"subscription_all":1,"subscription_all_write":1,"subscription_all_read":1,"transaction_all":1,"content_management":1,"email_all":1,"email_all_write":1,"email_all_read":1,"static_page_all":1,"static_page_all_write":1,"static_page_all_read":1,"banner_all":1,"banner_all_write":1,"banner_all_read":1,"analytics_management":1,"video_statistics":1,"region_wise_view_all":1,"top_category_all":1,"most_favourite_all":1,"most_viewed_all":1,"most_commented_all":1,"settings_all":1}',
                        'creator_id' => 1,
                        'updator_id' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                        'is_deletable' => 0,
                    ],
                    [
                       'name' => 'Admin',
                       'permissions' => '{"video_management":1,"videos_all":1,"category_all":1,"genre_all":1,"season_all":1,"preset_all":1,"user_management":1,"customer_all":1,"user_all":1,"usergroup_all":1,"subscription_all":1,"transaction_all":1,"content_management":1,"email_all":1,"static_page_all":1,"banner_all":1,"analytics_management":1,"video_statistics":1,"region_wise_view_all":1,"top_category_all":1,"most_favourite_all":1,"most_viewed_all":1,"most_commented_all":1}', 
                       'creator_id' => 1,
                        'updator_id' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                        'is_deletable' => 1,
                    ]
                ]
            );
        }
    }
}
