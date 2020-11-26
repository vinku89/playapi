<?php

use Illuminate\Database\Seeder;

class SiteLanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('site_languages')->get()->count() == 0){

            DB::table('site_languages')->insert([

                [	
                    'title' => 'English',
                    'code' => 'en',
                    'is_active' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [	
                    'title' => 'Hindi',
                    'code' => 'hi',
                    'is_active' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'French',
                    'code' => 'fr',
                    'is_active' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],


            ]);

        } else { echo "Table is not empty, therefore NOT "; }
    }
}
