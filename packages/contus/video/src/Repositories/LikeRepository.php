<?php

/**
 * Like Repository
 *
 * To manage the like/dislike functionality.
 *
 * @name LikeRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Like;

class LikeRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $like;

    /**
     * Construct method
     *
     * @param Like $like
     * @param LikeRepository $nlikeRepository
     */
    public function __construct(Like $like)
    {
        parent::__construct();
        $this->like = $like;
        $this->setRules([
          'video_id' => 'required',
          'status' => 'required|boolean'
        ]);
    }

    /**
     * Post like/dislike for videos
     *
     * @return array
     */
    public function postLike()
    {
        $this->_validate();
        $reqObj = $this->request->all();
        $response = [];
        $response['success'] = false;
        try {
            $video = Video::where($this->getKeySlugorId(), $reqObj['video_id'])->first();
            $this->like->where('user_id', auth()->user()->id)->where('video_id', $video->id)->where('type', Like::TYPE['dislike'])->delete();
            $query = $this->like->where('user_id', auth()->user()->id)->where('video_id', $video->id)->where('type', Like::TYPE['like']);
            $existCheck = $query->get();
            if ($existCheck->isEmpty() && $reqObj['status']) {
                $like = $this->like;
                $like->user_id = auth()->user()->id;
                $like->type = Like::TYPE['like'];
                $like->video_id = $video->id;
                $like->save();
            } elseif ($existCheck->isNotEmpty() && !$reqObj['status']) {
                $query->first()->delete();
            }
            $response['data'] = true;
            $response['success'] = true;
            $response['message'] = trans('video::videos.like_success');
        } catch (\Exception $e) {
            $response['message'] = trans('video::videos.like_error');
        }
        return $response;
    }

    /**
     * Post like/dislike for videos
     *
     * @return array
     */
    public function postDisLike()
    {
        $this->_validate();
        $reqObj = $this->request->all();
        $response = [];
        $response['success'] = false;
        try {
            $video = Video::where($this->getKeySlugorId(), $reqObj['video_id'])->first();
            $this->like->where('user_id', auth()->user()->id)->where('video_id', $video->id)->where('type', Like::TYPE['like'])->delete();
            $query = $this->like->where('user_id', auth()->user()->id)->where('video_id', $video->id)->where('type', Like::TYPE['dislike']);
            $existCheck = $query->get();
            if ($existCheck->isEmpty() && $reqObj['status']) {
                $like = $this->like;
                $like->user_id = auth()->user()->id;
                $like->type = Like::TYPE['dislike'];
                $like->video_id = $video->id;
                $like->save();
            } elseif ($existCheck->isNotEmpty() && !$reqObj['status']) {
                $query->first()->delete();
            }
            $response['data'] = true;
            $response['success'] = true;
            $response['message'] = trans('video::videos.dislike_success');
        } catch (\Exception $e) {
            $response['message'] = trans('video::videos.dislike_error');
        }
        return $response;
    }
}
