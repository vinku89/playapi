<?php

use Illuminate\Database\Seeder;
use Contus\Video\Models\Ffmpegstatus;
use Carbon\Carbon;

class FfmpegStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('ffmpeg_status')->count();
        if($existCount <= 0) {
            DB::unprepared("ALTER TABLE ffmpeg_status AUTO_INCREMENT = 1;");
            Ffmpegstatus::insert([
                    [
                        'status' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]
                ]
            );
        } 
    }
}
