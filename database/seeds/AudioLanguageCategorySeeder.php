<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AudioLanguageCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	$existCount = DB::table('audio_language_category')->count();
       	if($existCount <= 0) {
   			DB::unprepared("ALTER TABLE audio_language_category AUTO_INCREMENT = 1;");
   			DB::table('audio_language_category')->insert([
                        [
                            'language_name' => 'Tamil',
                            'order' => '1',
                            'is_active' => 1,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ],
                        [
                            'language_name' => 'English',
                            'order' => '1',
                            'is_active' => 1,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ],
                    ]
                );
	    }
    }
}
