<?php

use Illuminate\Database\Seeder;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Carbon\Carbon;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $factoryData = factory(Video::class,2)->insert();
        
        $faker = Faker\Factory::create();
        $limit = 1;

        $imageArray = ['5b83b4abf2add.jpeg', '5b7d4cdf9dacb.jpeg', '5b83966796938.jpeg', '5b8396877e71b.jpeg', '	
5b7e6c1fca990.jpeg', '5b7d82efe2765.jpeg', '5b8392f94e480.jpeg', '5b83936ded260.jpeg'];
		$posterImageArray = ['5b83b4b8dc826.jpeg', '5b839656a9e7d.jpeg', '5b83966eeae17.jpeg', '5b839693aee75.jpeg', '5b7e6c2cc58c3.jpeg', '5b83930743973.jpeg', '5b839373611ab.jpeg'];

		// Function to fetch all Active category Array
		$categoryArray = Category::where('is_active',1)->where('level', '!=', 0)->pluck('id')->toArray();

		if(!empty($categoryArray)) {
	        for($i=0; $i<$limit; $i++) {
	        	$title = $faker->sentence(3, true);
	        	$slug  = str_replace(' ', '-', $title).'-'.$faker->unique()->randomDigit;
		        $videoObj = DB::table('videos')->insert([
			        'title' => $title,
			        'slug' => $slug,
			        'video_url' => 'https://contus-vplay.s3.ap-southeast-1.amazonaws.com/69-video-295564.mp4',
			        'description' => $faker->text(200),
			        'short_description' => $faker->text(200),
			        'thumbnail_image' => $imageArray[array_rand($imageArray, 1)],
			        'thumbnail_path' => $imageArray[array_rand($imageArray, 1)],
			        'video_duration' => '2:27',
			        'is_hls' => 1,
			        'hls_playlist_url' => 'https://d1xxzl3j6dvosn.cloudfront.net/FFMPEG/1508-23-2018_0159pm/playlist.m3u8',
			        'aws_prefix' => 'FFMPEG/1508-23-2018_0159pm',
			        'is_live' => 0,
			        'fine_uploader_uuid' => 'e738b638-3f61-4936-883c-ad6d43118469',
			        'fine_uploader_name' => 'WONDERWOMANOfficialTrailer32017HDmp41535012997.mp4',
			        'job_status' => 'Complete',
			        'is_featured' => 0,
			        'is_subscription' => 0,
			        'trailer_status' => 0,
			        'published_on' => Carbon::now()->toDateTimeString(),
			        'presenter' => $faker->name,
			        'notification_status' => 1,
			        'is_active' => 1,
			        'creator_id' => 1,
			        'updator_id' => 1,
			        'is_archived' => 0,
			        'created_at' => Carbon::now()->toDateTimeString(),
			        'updated_at' => Carbon::now()->toDateTimeString(),
			        'poster_image' => $posterImageArray[array_rand($posterImageArray, 1)]
			    ]);

			    $videoObj = DB::table('video_categories')->insert([
			    	'video_id' => DB::getPdo()->lastInsertId(),
			    	'category_id' => $categoryArray[array_rand($categoryArray, 1)],
			    	'created_at' => Carbon::now()->toDateTimeString(),
			        'updated_at' => Carbon::now()->toDateTimeString()
			    ]);

			}
        }
    }
}
