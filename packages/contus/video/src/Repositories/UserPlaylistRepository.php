<?php

/**
 * Video Repository
 *
 * To manage the functionalities related to videos
 *
 * @name VideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http: www.gnu.org/copyleft/gpl.html
 *
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\UserPlaylist;
use Contus\Video\Models\PlaylistVideos;
use Contus\Video\Models\Video;


class UserPlaylistRepository extends BaseRepository
{

    /**
     * class property to hold the instance of Video Model
     *
     * @var \Contus\Video\Models\Video
     */
    public $video;
 

    /**
     * Construct method initialization
     *
     * Validation rule for user verification code and forgot password.
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * Set other class objects to properties of this class.
         */
        $this->user_playlist = new UserPlaylist ();
        $this->playlist_videos = new PlaylistVideos ();
    }

    public function addPlayList() {
        $result['error'] = false;
        $result['message'] = '';
        $result['data'] = [];
        $inputArray = $this->request->all();
        $userId     = (!empty(authUser()->id)) ? authUser()->id : 0;

        if(!empty($inputArray['id'])) {
            $this->setRules(['videos' => 'sometimes']);
        }
        else {
            $this->setRules(['name' => 'required', 'videos' => 'sometimes']);
        }
        $this->validate($this->request, $this->getRules());

        $validateCreate = $this->validatePlaylist();

        if(!$validateCreate['error']) {
            if(!empty($inputArray['name'])) {
                $this->user_playlist->name = $inputArray['name'];
            }
            $this->user_playlist->user_id = (string) $userId;
            $this->user_playlist->is_active = 1;
            $this->user_playlist->is_admin = 0;
            $this->user_playlist->save();

            if(!empty($inputArray['videos'])) {
                $videoArray     = $this->fetchVideos();
                $playlistVideos = $this->validatePlaylistVideo($this->user_playlist->id, $videoArray);
                foreach($videoArray as $index=>$video) {
                    $foundKey = array_search($video, array_column($playlistVideos, 'video_id'));
                    if(empty($foundKey) && $foundKey !== 0) {
                        $videoPlaylist              = new PlaylistVideos();
                        $videoPlaylist->playlist_id = $this->user_playlist->id;
                        $videoPlaylist->video_id    = (string) $video;
                        $videoPlaylist->is_active   = 1;
                        $videoPlaylist->save();
                    }
                }

            }
        }
        else {
            $result['error'] = true;
            $result['message'] = $validateCreate['message'];
        }
        return $result;
    }

    /**
     * Function to validate the playlist creation and playlist update process
     * @return Array
     */
    public function validatePlaylist() {
        $result['error'] = false;
        $result['message'] = '';
        $inputArray = $this->request->all();

        $existing = $this->validateExisitngPlaylist();
        if(!empty($existing)) {
            $result['error'] = true;
            $result['message'] = trans('video::userplaylist.playlist_exist');
        }

        if(!empty($inputArray['id']) && !$result['error']) {
            $userPlayList = $this->fetchPlaylist($inputArray['id']);
            if(empty($userPlayList)) {
                $result['error'] = true;
                $result['message'] = trans('video::userplaylist.playlist_not_found');
            }
            else {
                $this->user_playlist = $userPlayList;
            }
        }
        return $result;
    }

    /**
     * Function to fetch corresponding video id from the given video slug
     * @return [type] [description]
     */
    public function fetchVideos() {
        $inputArray = $this->request->all();
        $videoArray     = explode(',', $inputArray['videos']);
        if(!isMobile()) {
            $videoArray = Video::whereIn('slug', $videoArray)->pluck('id', 'slug')->toArray();
        }
        return $videoArray;
    }

    /**
     * Function to fetch given playlist informations
     * @return Array
     */
    public function fetchPlaylist($playlist) {
        return UserPlaylist::where('is_active',1)->where('_id', $playlist)->first();
    }

    /**
     * Function to validate duplicate playlist name for the same user
     * @return Array
     */
    public function validateExisitngPlaylist() {
        $existingInfo = [];
        $inputArray = $this->request->all();
        $userId     = auth()->user()->id;

        $userInfo = UserPlaylist::where('is_active',1)->where('name', $inputArray['name'])->where('user_id', (string) $userId);
        if(!empty($inputArray['id'])) {
            $userInfo->where('_id', '!=', $inputArray['id']);
        }
        return $userInfo->first();
    }

    /**
     * Function to validate if the videos are already associated with given playlist
     * @return Array
     */
    public function validatePlaylistVideo($playlist, $video) {
        $videoArray = (is_array($video)) ? $video : [$video];
        return PlaylistVideos::where('is_active', 1)->where('playlist_id', $playlist)->whereIn('video_id', $videoArray)->get()->toArray();
    }

    /**
     * Function to users saved playlist
     * @return Array - Saved Playlist informations
     */
    public function fetchSavedPlaylist() {
        $result['error'] = false;
        $result['message'] = '';
        try {
            $userId         = (!empty(authUser()->id)) ? authUser()->id : 0;
            $result['data'] = UserPlaylist::where('is_active',1)->where('user_id', (string) $userId)->orderBy('_id', 'desc')->paginate(10);
        }
        catch(\Exception $e) {
            $result['error'] = true;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Function to fetch all the videos in the given playlist
     * @return Array - List of saved videos
     */
    public function fetchPlaylistVideos() {
        $result['error'] = false;
        $result['message'] = '';

        $this->setRules(['playlist_id' => 'required']);
        $this->validate($this->request, $this->getRules());

        try {
            $userId         = (!empty(authUser()->id)) ? authUser()->id : 0;
            $inputArray     = $this->request->all();

            $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.thumbnail_image, videos.hls_playlist_url, videos.id as is_favourite, videos.id as collection, videos.poster_image,videos.is_live, videos.scheduledStartTime,videos.is_premium, videos.price';
            $result['data']['playlist_info'] = UserPlaylist::Select('_id','created_at', 'name')->where('is_active',1)->where('_id', (string) $inputArray['playlist_id'])->first();
            $result['data']['playlist_videos'] = PlaylistVideos::with(['video' => function($query) use ($fields) {
                $query->selectRaw($fields);
            }])->where('is_active',1)->where('playlist_id', (string) $inputArray['playlist_id'])->orderBy('_id', 'desc')->paginate(10);
        }
        catch(\Exception $e) {
            $result['error'] = true;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

    public function deletePlaylist() {
        $result['error'] = false;
        $result['message'] = '';

        $this->setRules(['playlist_id' => 'required']);
        $this->validate($this->request, $this->getRules());

        try {
            $userId         = (!empty(authUser()->id)) ? authUser()->id : 0;
            $inputArray     = $this->request->all();
            UserPlaylist::where('_id', (string) $inputArray['playlist_id'])->delete();
            PlaylistVideos::where('playlist_id', (string) $inputArray['playlist_id'])->delete();
        }
        catch(\Exception $e) {
            $result['error'] = true;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

    public function deletePlaylistVideos() {
        $result['error'] = false;
        $result['message'] = '';

        $this->setRules(['playlist_id' => 'required', 'video_id' => 'required']);
        $this->validate($this->request, $this->getRules());

        try {
            $userId         = (!empty(authUser()->id)) ? authUser()->id : 0;
            $inputArray     = $this->request->all();

            if(!isMobile()) {
                $videoInfo = Video::SelectRaw('id, slug')->where($this->getKeySlugorId(), $inputArray['video_id'])->first();
                $inputArray['video_id'] = (!empty($videoInfo)) ? $videoInfo->id : $inputArray['video_id'];
            }
            PlaylistVideos::where('playlist_id', (string) $inputArray['playlist_id'])->where('video_id', (string) $inputArray['video_id'])->delete();
        }
        catch(\Exception $e) {
            $result['error'] = true;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
}
