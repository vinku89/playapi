<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(AdminUserGroupTableSeeder::class);
         $this->call(AdminUserTableSeeder::class);
         $this->call(EmailTemplateTableSeeder::class);
         $this->call(FfmpegStatusSeeder::class);
         $this->call(PaymentMethodsSeeder::class);   
         $this->call(PresetTableSeeder::class);
         $this->call(SettingCategoryTableSeeder::class);
         $this->call(StaticPagesTableSeeder::class);
         $this->call(SubscriptionPlanSeeder::class); 
         $this->call(SiteLanguageSeeder::class);
         $this->call(AlbumsTableSeeder::class); 
         $this->call(AudioPresetTableSeeder::class); 
         $this->call(AudioLanguageCategorySeeder::class);
         $this->call(GeoCountriesRegionsSeeder::class);
         $this->call(GeofencingSettingSeeder::class);
         $this->call(CountriesSeeder::class); 
    }
}