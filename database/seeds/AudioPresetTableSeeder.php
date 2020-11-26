<?php

use Illuminate\Database\Seeder;
use Contus\Audio\Models\AudioPreset;
use Carbon\Carbon;

class AudioPresetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('audio_presets')->count();
        if($existCount <= 0) {
            DB::unprepared("ALTER TABLE audio_presets AUTO_INCREMENT = 1;");
            DB::table('audio_presets')->insert([
                    [
                        'name' => 'Vplayed : HLS Audio - 160k',
                        'aws_id' => '1543817082256-v07q4y',
                        'format' => 'ts',
                        'description' => 'Vplayed : HLS Audio - 160k',
                        'thumbnail_format' => 'png',
                        'is_active' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'name' => 'Vplayed : HLS Audio - 320k',
                        'aws_id' => '1543817228759-1ew05h',
                        'format' => 'ts',
                        'description' => 'Vplayed : HLS Audio - 320k',
                        'thumbnail_format' => 'png',
                        'is_active' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                ]
            );
        }
    }
}
