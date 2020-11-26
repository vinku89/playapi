<?php

use Illuminate\Database\Seeder;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $existCount = DB::table('audio_albums')->count();
        if($existCount <= 0) {
            DB::unprepared("ALTER TABLE audio_albums AUTO_INCREMENT = 1;");
            DB::table('audio_albums')->insert([
                [
                    'album_name' => 'Queen - Greatest Hits (1) [1 hour long]',
                    'album_artist_id' => 1,
                    'album_description' => 'The audio is made up of some of Queen\'s best known singles since their first chart appearance in 1974',
                    'audio_language_category_id' => 1,
                    'is_active' => 1,
                    'album_release_date' => '2018-12-14',
                ],
                [
                    'album_name' => '7UP Madras Gig - Orasaadha | Vivek - Mervin',
                    'album_artist_id' => 1,
                    'album_description' => 'Orasaadha, the second track from Season 1 of #7UPMadrasGig is here! There is an unmistakable instant appeal to this snappy Electro-Pop romantic single and its sure to stick',
                    'audio_language_category_id' => 1,
                    'is_active' => 1,
                    'album_release_date' => '2018-12-14',
                ],
                [
                    'album_name' => 'Mera Jahan Video Song | Gajendra Verma | Latest Hindi Songs 2017 | T-Series',
                    'album_artist_id' => 1,
                    'album_description' => 'T-Series presents the brand new song "Mera Jahan" in the voice of Gajendra Verma.',
                    'audio_language_category_id' => 1,
                    'is_active' => 1,
                    'album_release_date' => '2018-12-14',
                ],
            ]);
        }
    }
}
