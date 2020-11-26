<?php

/**
 * Video Controller
 *
 * To manage the Video such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 *
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Contus\Video\Models\UserPlaylist;
use Contus\Video\Repositories\UserPlaylistRepository;


use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class UserPlaylistController extends ApiController
{
    public $awsRepository;
    /**
     * constructor funtion for video controller
     *
     * @param FrontVideoRepository $videosRepository
     */
    public function __construct(UserPlaylistRepository $userplaylistRepository)
    {
        parent::__construct();
        $this->repository = $userplaylistRepository;


        $this->repoArray = ['repository'];
    }

    /**
     * Function to create playlist
     * @return [type] [description]
     */
    public function createPlaylist() {
        $inputArray = $this->request->all();
        $result = $this->repository->addPlayList();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], $result['message'], 422 );
        } else {
            if(!empty($inputArray['id'])) {
                $message = (!empty($inputArray['name'])) ? trans('video::userplaylist.playlist_updated') : trans('video::userplaylist.playlist_video_updated');
            }
            else {
                $message = trans('video::userplaylist.playlist_created');
            }
            return $this->getSuccessJsonResponse(['message' => $message]);
        }
    }

    /**
     * Function to fetch users saved playlist
     * @return Object - Playlist videos
     */
    public function fetchPlaylist() {
        $result = $this->repository->fetchSavedPlaylist();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], $result['message'], 422 );
        } else {
            $userPlaylist['my_playlist'] = $result['data'];
            return $this->getSuccessJsonResponse(['message' => trans('video::userplaylist.playlist_fetch_success'), 'response' => $userPlaylist]);
        }
    }

    /**
     * Function to fetch videos in the given playlist
     * @return Object - Playlist videos
     */
    public function fetchPlaylistVideos() {
        $result = $this->repository->fetchPlaylistVideos();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], trans('video::userplaylist.fetch_error'), 422 );
        } else {
            return $this->getSuccessJsonResponse(['message' => trans('video::userplaylist.playlist_fetch_success'), 'response' => $result['data']]);
        }
    }

    public function deletePlaylist() {
        $result = $this->repository->deletePlaylist();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], $result['message'], 422 );
        } else {
            // $userPlaylist['my_playlist'] = $result['data'];
            return $this->getSuccessJsonResponse(['message' => trans('video::userplaylist.playlist_delete_success')]);
        }    
    }

    public function deletePlaylistVideos() {
        $result = $this->repository->deletePlaylistVideos();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], $result['message'], 422 );
        } else {
            // $userPlaylist['my_playlist'] = $result['data'];
            return $this->getSuccessJsonResponse(['message' => trans('video::userplaylist.playlist_video_delete_success')]);
        }    
    }
}
