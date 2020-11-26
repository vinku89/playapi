<?php

use Illuminate\Database\Seeder;
use Contus\Video\Models\VideoPreset;
use Carbon\Carbon;

class PresetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('video_presets')->count();
        if($existCount <= 0) {
            DB::unprepared("ALTER TABLE video_presets AUTO_INCREMENT = 1;");
            VideoPreset::insert([
                    [
                        'name' => 'vplayed-hls-270p',
                        'aws_id' => '1535369224372-4uve8a',
                        'format' => 'ts',
                        'description' => 'vplayed-hls-270p',
                        'thumbnail_format' => 'png',
                        'is_active' => 0,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'name' => 'vplayed-hls-360p',
                        'aws_id' => '1535368874571-ic06ub',
                        'format' => 'ts',
                        'description' => 'vplayed-hls-360p',
                        'thumbnail_format' => 'png',
                        'is_active' => 0,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'name' => 'vplayed-hls-480p',
                        'aws_id' => '1535369054907-8gtf3o',
                        'format' => 'ts',
                        'description' => 'vplayed-hls-480p',
                        'thumbnail_format' => 'png',
                        'is_active' => 0,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'name' => 'vplayed-hls-720p',
                        'aws_id' => '1535368972759-t3s3to',
                        'format' => 'ts',
                        'description' => 'vplayed-hls-720p',
                        'thumbnail_format' => 'png',
                        'is_active' => 0,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ],
                    [
                        'name' => 'vplayed-hls-1080p',
                        'aws_id' => '1535369387593-e9aa2h',
                        'format' => 'ts',
                        'description' => 'vplayed-hls-1080p',
                        'thumbnail_format' => 'png',
                        'is_active' => 0,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]
                ]
            );
        }
    }
}
