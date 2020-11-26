<?php

/**
 * AlbumRepository
 *
 * To manage the audio album management such as create, edit and delete
 *
 * @name AlbumRepository
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Audio\Repositories;

use Carbon\Carbon;
use Contus\Audio\Models\Albums;
use Contus\Audio\Models\Artist;
use Contus\Audio\Models\AudioGenres;
use Contus\Audio\Models\AudioLanguageCategory;
use Contus\Audio\Models\Audios;
use Contus\Audio\Models\FavouriteAlbum;
use Contus\Audio\Models\AudioAnalytics;
use Contus\Audio\Traits\AudioHelperTrait;
use Contus\Base\Repository as BaseRepository;

class AlbumRepository extends BaseRepository
{
    use AudioHelperTrait;
    /**
     * Class construct method initialization
     */
    public function __construct()
    {        
        parent::__construct();
        $this->albums = new Albums();
        $this->audios = new Audios();
        $this->artists = new Artist();
        $this->audio_language = new AudioLanguageCategory();
        $this->favouriteAlbum = new FavouriteAlbum();
        $this->audioGenre = new AudioGenres();
        $this->trendingNow = new AudioAnalytics();
        $this->records_per_page = config('contus.audio.audio.record_per_page');
        $this->album_tracks_per_page = config('contus.audio.audio.album_tracks_per_page');
    }
    /**
     * Method to get homepage contents by type
     *
     * @vendor contus
     * @package Audio
     * @param string $type
     * @return array
     */
    public function getAlbumsByType($type)
    {
        return app('cache')->tags([getCacheTag(), 'audio_albums', 'audios', 'audio_artists', 'audio_language_category'])->remember(getCacheKey() . '_home_page_section_' . $type, getCacheTime(), function () use ($type) {
            $title = '';
            $albumModel = $this->albums;
            switch ($type) {
                case 'weekly_top':
                /** To get weekly top 15 songs based on past 7 days and highest audio's play count */
                    $title = 'Weekly Top 15 Songs';
                    $getWeeklyTop15 = $this->getWeeklyTopFifteenId($this->trendingNow, 'audio_id');
                    $getWeeklyTop15Ids = $getWeeklyTop15['weekly_top_id'];
                    $placeholders = $getWeeklyTop15['placeholders'];
                    $albumModel = $this->audios->whereIn('id', $getWeeklyTop15Ids)
                    ->orderByRaw("field(id,{$placeholders})", $getWeeklyTop15Ids);
                    break;
                case 'trending_now':
                /** To get trending now songs based on past 24 hours and highest audio's play count */
                    $title = 'Trending Now';
                    $getTrendingNow = $this->getTrendingNowSongId($this->trendingNow, 'audio_id');
                    $trendingSongIds = $getTrendingNow['trending_id'];
                    $placeholders = $getTrendingNow['placeholders'];
                    $albumModel = $this->audios->whereIn('id', $trendingSongIds)
                    ->orderByRaw("field(id,{$placeholders})", $trendingSongIds);
                    break;
                case 'trending_week':
                /** To get trending now songs based on past 1 week and highest audio's play count */
                    $title = 'Trending Now';
                    $getTrendingNow = $this->getTrendingWeekSongId($this->trendingNow, 'audio_id');
                    $trendingSongIds = $getTrendingNow['trending_id'];
                    $placeholders = $getTrendingNow['placeholders'];
                    $albumModel = $this->audios->whereIn('id', $trendingSongIds)
                    ->orderByRaw("field(id,{$placeholders})", $trendingSongIds);
                    break;
                case 'new':
                    $title = 'New Releases';
                    $albumModel = $albumModel->orderBy('id', 'desc');
                    break;
                case 'popular':
                    /** To get popular albums based on the most favourited albums */
                    $title = 'Popular Albums';
                    $getMostFavAlbumIds = $this->getMostFavouritedAlbum($this->favouriteAlbum, 'album_id');                    
                    $mostFavAlbumIds = $getMostFavAlbumIds['popular_id'];
                    $placeholders = $getMostFavAlbumIds['placeholders'];
                    $albumModel = $albumModel->whereIn('id', $mostFavAlbumIds)
                        ->orderByRaw("field(id,{$placeholders})", $mostFavAlbumIds);
                    break;
                // case 'chartbusters':
                //     $title = 'Chartbusters';
                //     $genreId = $this->audioGenre->select('id')->first()->toArray();
                //     $albumModel = $albumModel->where('genre_id', $genreId['id'])->orderBy('id', 'desc');
                //     break;
                default:
                    break;
            }
            $albumModel = $albumModel->paginate($this->records_per_page)->toArray();
            $albumModel['category_name'] = $title;
            $albumModel['type'] = $type;
            return $albumModel;
        });
    }

    /**
     * Method to get contents for album detail page
     *
     * @vendor Contus
     * @package Audio
     * @return array
     */
    public function albumDetails()
    {
        $result = array();
        $this->setRules(['slug' => 'required']);
        $this->validate($this->request, $this->getRules());
        $slug = $this->request->slug;
        return app('cache')->tags([getCacheTag(), 'audio_albums', 'audios', 'audio_artists', 'audio_language_category'])->remember(getCacheKey() . '_album_detail_' . $slug, getCacheTime(), function () use ($slug) {
            $albumBuilder = $this->albums->with('albumTracks');
            $albumData = $albumBuilder->where($this->getKeySlugorId(), $slug)->first();
            (is_null($albumData)) ? $this->throwJsonResponse(false, 404, trans('audio::album.404_slug_response')) : '';
            $albumArtistId = $albumData->album_artist_id;
            $result['album_info'] = $albumData;
            $result['related_albums'] = $this->getRelatedAlbums($this->albums, $albumArtistId, $albumData->id);
            return $result;
        });

    }
    /**
     * Method to get the related albums more content on slider
     *
     * @vendor Contus
     * @package Audio
     * @return array
     */
    public function getMoreRelatedAlbums()
    {
        $this->setRules(['artist_id' => 'required', 'album_id' => 'sometimes']);
        $this->validate($this->request, $this->getRules());
        $albumArtistId = $this->request->artist_id;
        $albumID = $this->request->album_id ?: null;
        $albumBuilder = $this->albums->with('albumTracks');
        return $this->getRelatedAlbums($albumBuilder, $albumArtistId, $albumID);
    }
    /**
     * Method to get the list of albums for browse menu
     *
     * @vendor Contus
     * @package Audio
     * @return array
     */
    public function browseInfo()
    {
        $result = array();
        if ($this->request->has('language_id')) {
            $languageBuilder = $this->audio_language->selectRaw('*, id as album_list');
            $languageBuilder = $languageBuilder->where('id', $this->request->language_id);
            $languageBuilder = $languageBuilder->get()->toArray();
            $result['album_data'] = $languageBuilder[0]['album_list'];
        } else {
            $result['languages'] = $this->audio_language->select('id', 'language_name')
                ->orderBy('order', 'ASC')
                ->orderBy('id', 'DESC')->get()->toArray();
            $albumBuilder = $this->albums;
            $resultQuery = ($this->request->has('search'))
            ? $this->browseAlbumsAlphanumericWise($albumBuilder, $this->request->search)
            : $albumBuilder;
            $result['album_data'] = $resultQuery->paginate($this->records_per_page)->toArray();
        }

        return $result;
    }
    /**
     * Method to get the list of albums audio tracks
     *
     * @vendor Contus
     * @package Audio
     * @return array
     */
    public function albumAudioTracks()
    {
        $this->setRules(['slug' => 'required']);
        $this->validate($this->request, $this->getRules());
        $inputArray = $this->request->all();
        if (!isMobile()) {
            $albumInfo = $this->albums->where($this->getKeySlugorId(), $inputArray['slug'])->first();
            $albumId = (!empty($albumInfo)) ? $albumInfo->id : 0;
        } else {
            $albumId = $inputArray['slug'];
        }
        return $this->audios->selectRaw('*')->where('album_id', $albumId)->paginate($this->album_tracks_per_page)->toArray();
    }
    /**
     * Method to get featured artists for the homepage
     *
     * @vendor Contus
     * @package Audio
     * @return array
     */
    public function getFeaturedArtists()
    {
        $getPopularIds = $this->getPopularIdFromAudioPlayCount($this->audios, 'audio_artist_id');
        $popularArtistIds = $getPopularIds['trending_id'];
        $placeholders = $getPopularIds['placeholders'];
        $artistModel = $this->artists->whereIn('id', $popularArtistIds)
            ->orderByRaw("field(id,{$placeholders})", $popularArtistIds)
            ->paginate($this->records_per_page)->toArray();
        $artistModel['category_name'] = 'Featured Artists';
        $artistModel['type'] = 'featured_artists';
        return $artistModel;
    }
}
