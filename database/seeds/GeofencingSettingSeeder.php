<?php

use Illuminate\Database\Seeder;

class GeofencingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('geofencing_setting')->get()->count() == 0) {
            DB::table('geofencing_setting')->insert([
                [
                    'type' => 'all_countries', 
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),              
                ],
                [
                    'type' => 'individual_allowed_countries',
                    'is_active' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'type' => 'global_allowed_countries',
                    'is_active' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }
    }
}
