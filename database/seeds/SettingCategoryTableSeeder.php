<?php

use Illuminate\Database\Seeder;
use Contus\User\Models\SettingCategory;
use Contus\User\Models\Setting;
use Contus\User\Repositories\SettingsRepository;

class SettingCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('settings')->count();
        if($existCount <= 0) {
            DB::table('setting_categories')->delete();
            DB::table('settings')->delete();
            // Auto increment value set to 1
            DB::unprepared("ALTER TABLE setting_categories AUTO_INCREMENT = 1;");
            DB::unprepared("ALTER TABLE settings AUTO_INCREMENT = 1;");

            $settingsCategories = [
                '1' => [
                    'id' => 1,
                    'name' => 'General Settings',
                    'slug' => 'general-settings',
                    'parent_id' => NULL,
                ],
                '2' => [
                    'id' => 2,
                    'name' => 'Site Settings',
                    'slug' => 'site-settings',
                    'description' => 'Site Settings',
                    'parent_id' => 1,
                    'settings' => [
                        [
                            'setting_name' => 'site_name',
                            'setting_value' => 'Contus VPlayed',
                            'display_name' => 'Site Name',
                            'type' => 'text',
                            'option' => NULL,
                            'class' => NULL,
                            'order' => 1,
                            'setting_category_id' => 2,
                            'description' => 'Specify site name',
                        ],
                        [
                            'setting_name' => 'page_title',
                            'setting_value' => 'Contus VPlayed - Video Platform',
                            'display_name' => 'Page Title',
                            'type' => 'text',
                            'order' => 2,
                            'setting_category_id' => 2,
                            'description' => 'Specify few words about the site.',
                        ],
                        [
                            'setting_name' => 'page_keywords',
                            'setting_value' => 'Contus VPlayed',
                            'display_name' => 'Page Keywords',
                            'type' => 'text',
                            'order' => 3,
                            'setting_category_id' => 2,
                        ],
                        [
                            'setting_name' => 'page_description',
                            'setting_value' => 'Contus VPlayed - Video Platform',
                            'display_name' => 'Page Description',
                            'type' => 'text',
                            'order' => 4,
                            'setting_category_id' => 2,
                        ],
                        [
                            'setting_name' => 'favicon',
                            'setting_value' => 'favicon.ico',
                            'display_name' => 'Fav Icon',
                            'type' => 'image',
                            'order' => 5,
                            'setting_category_id' => 2,
                            'description' => 'Recommended favicon resolution is 32x32 Image format should be ico or png',
                            'is_hidden' => 1

                        ],
                        [
                            'setting_name' => 'logo',
                            'setting_value' => 'logo.png',
                            'display_name' => 'Site Logo',
                            'type' => 'image',
                            'order' => 6,
                            'setting_category_id' => 2,
                            'description' => 'Recommended site logo resolution is 45x45 Image format should be png or jpg',
                            'is_hidden' => 1,
                        ],
                        [
                            'setting_name' => 'site_email_id',
                            'setting_value' => 'support@vplayed.com',
                            'display_name' => 'Site Email Address',
                            'type' => 'email',
                            'order' => 7,
                            'setting_category_id' => 2,
                            'description' => 'Site Email Address',
                        ],
                        [
                            'setting_name' => 'site_mobile_number',
                            'setting_value' => '9840705435',
                            'display_name' => 'Site Mobile Number',
                            'type' => 'text',
                            'order' => 8,
                            'setting_category_id' => 2,
                            'description' => 'Site Mobile number',
                        ],
                        [
                            'setting_name' => 'site_local_address',
                            'setting_value' => 'Contus, Kamak Towers, No.12 A, (SP), 6th floor, Ekkatuthangal, Guindy',
                            'display_name' => 'Site Local Address',
                            'type' => 'text',
                            'order' => 9,
                            'setting_category_id' => 2,
                            'description' => 'Site Local Address',
                        ],
                        [
                            'setting_name' => 'video_view_count',
                            'setting_value' => '0',
                            'display_name' => 'Videos View Count',
                            'type' => 'text',
                            'order' => 10,
                            'setting_category_id' => 2,
                            'description' => 'Global videos view count',
                        ],
                    ],
                ],                
                '5' => [
                    'id' => 5,
                    'name' => 'Site Link Settings',
                    'slug' => 'site-link-settings',
                    'parent_id' => NULL,
                ],
                '6' => [
                    'id' => 6,
                    'name' => 'Site Link Settings',
                    'slug' => 'site-external-link-settings',
                    'parent_id' => 5,
                    'settings' => [
                        [
                            'setting_name' => 'fb_link',
                            'setting_value' => 'https://www.facebook.com/vplayed/',
                            'display_name' => 'Facebook Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 13,
                            'setting_category_id' => 6,
                            'description' => 'Facebook page link for the website'
                        ],
                        [
                            'setting_name' => 'twitter_link',
                            'setting_value' => 'https://twitter.com/vplayed',
                            'display_name' => 'Twitter Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 14,
                            'setting_category_id' => 6,
                            'description' => 'Twitter page link for the website'
                        ],
                        [
                            'setting_name' => 'google_link',
                            'setting_value' => '',
                            'display_name' => 'Google Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 15,
                            'setting_category_id' => 6,
                            'description' => 'Google page link for the website'
                        ],
                        [
                            'setting_name' => 'instagram_link',
                            'setting_value' => 'https://www.instagram.com/vplayed/',
                            'display_name' => 'Instagram Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 16,
                            'setting_category_id' => 6,
                            'description' => 'Instagram page link for the website'
                        ],
                        [
                            'setting_name' => 'android_app_link',
                            'setting_value' => '',
                            'display_name' => 'Android App Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 17,
                            'setting_category_id' => 6,
                            'description' => 'Playstore link for the Android application'
                        ],
                        [
                            'setting_name' => 'ios_app_link',
                            'setting_value' => '',
                            'display_name' => 'iOS App Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 18,
                            'setting_category_id' => 6,
                            'description' => 'Appstore link for the iOS Application'
                        ],
                        [
                            'setting_name' => 'aod_android_app_link',
                            'setting_value' => '',
                            'display_name' => 'AOD Android App Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 17,
                            'setting_category_id' => 6,
                            'description' => 'Playstore link for the AOD Android application'
                        ],
                        [
                            'setting_name' => 'aod_ios_app_link',
                            'setting_value' => '',
                            'display_name' => 'AOD iOS App Link',
                            'type' => 'text',
                            'option' => null,
                            'order' => 18,
                            'setting_category_id' => 6,
                            'description' => 'Appstore link for the AOD iOS Application'
                        ],
                    ],
                ],
            ];
            foreach ($settingsCategories as $key => $value) {
                $setting_category = $value;
                unset($setting_category['settings']);
                (new SettingCategory())->fill($setting_category)->save();
                if (isset($value['settings']) && count($value['settings']) > 0) {
                    foreach ($value['settings'] as $setting) {
                        (new Setting())->fill($setting)->save();
                    }
                }
            }
            (new SettingsRepository(new Setting(), new SettingCategory()))->generateSettingsCache();
            (new SettingsRepository(new Setting(), new SettingCategory()))->generateValidationCache();
        }
    }
}