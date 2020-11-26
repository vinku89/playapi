<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name CommentsRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Comment;
use Illuminate\Support\Facades\Config;
use Contus\Video\Models\ReplyComment;
use Contus\Notification\Repositories\NotificationRepository;

class CommentsRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $comments;

    /**
     * Construct method
     *
     * @param Comment $comment
     * @param NotificationRepository $notificationRepository
     */
    public function __construct(Comment $comment, NotificationRepository $notificationRepository)
    {
        parent::__construct();
        $this->comments = $comment;
        $this->notification = $notificationRepository;
    }

    /**
     * Method to add comment by validating the user
     *
     * @return number
     */
    public function addComment()
    {
        $this->setRule('comment', 'filled');
        if ($this->_validate()) {
            $this->comments->comment = $this->request->comment;
            $this->comments->video_id = $this->request->video_id;
            if (config()->get('auth.providers.users.table') == 'users') {
                $this->comments->user_type = 'admin';
                $this->comments->user_id = $this->authUser->id;
            } else {
                $this->comments->user_type = 'customer';
                $this->comments->customer_id = $this->authUser->id;
            }
            $this->comments->is_active = 1;
            $this->comments->creator_id = $this->authUser->id;
            return ($this->comments->save()) ? 1 : 0;
        }
    }

    /**
     * Method to add comment by validating the user based on parent comment
     *
     * @return number
     */
    public function addChildComment()
    {
        $this->setRules(['comment' => 'required'], ['parent_id' => 'required'], ['video_id' => 'required|integer']);
        $this->_validate();
        $this->comments = $this->comments->find($this->request->parent_id);
        $result['status']   = false;
        $result['message']  = trans('video::videos.reply_commentinsert.error');
        if (is_object($this->comments) && !empty($this->comments->id)) {
            $attachComment = new Comment();
            $attachComment->video_id = $this->request->video_id;
            $attachComment->comment = $this->request->comment;
            $attachComment->parent_id = $this->comments->id;
            if (config()->get('auth.providers.users.table') == 'users') {
                $attachComment->user_type = 'admin';
                $attachComment->user_id = $this->authUser->id;
            } else {
                $attachComment->user_type = 'customer';
                $attachComment->customer_id = $this->authUser->id;
            }
            $attachComment->creator_id = $this->authUser->id;
            if($attachComment->save()) {
                $this->notification->notify('reply_comment', $this->comments->id);
                $result['status']   = true;
                $result['message']  = trans('video::videos.reply_commentinsert.success');
            }
        }
        else {
            $result['message']  = trans('video::videos.comment_not_found');
        }
        return $result;
    }

    /**
     * Function to get all comments
     *
     * @return object
     */
    public function getAllComments()
    {
        return $this->comments->get();
    }

    /**
     * Function to update the status for comments
     *
     * @param int $id
     * @param string $status
     * @return number
     */
    public function updateStatus($id, $status)
    {
        $comment = $this->comments = $this->comments->find($id);
        $comment->is_active = $status;
        $return = $comment->save();
        $this->notification->notify('comment', $id);
        return ($return) ? 1 : 0;
    }

    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings()
    {
        return ['heading' => [['name' => trans('video::videos.name'), 'value' => 'name', 'sort' => false], ['name' => trans('video::videos.student'), 'value' => '', 'sort' => false], ['name' => 'comments', 'value' => '', 'sort' => false], ['name' => trans('video::playlist.status'), 'value' => 'is_active', 'sort' => false], ['name' => trans('video::collection.added_on'), 'value' => '', 'sort' => false]]];
    }

    /**
     * Get headings for grid
     *
     * @return array
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->comments)->setEagerLoadingModels(['video' => function ($query) {
            $query->get();
        }, 'customer' => function ($query) {
            $query->get();
        }]);
        return $this;
    }

    /**
     * Function to apply filter for search of Comments grid
     *
     * @param mixed $searchComments
     * @return \Illuminate\Database\Eloquent\Builder $searchComments The builder object of comments grid.
     */
    protected function searchFilter($searchComments)
    {
        $searchRecordGroup = $this->request->has('searchRecord') && is_array($this->request->input('searchRecord')) ? $this->request->input('searchRecord') : [];
        $title = $is_active = null;
        extract($searchRecordGroup);
        if ($title) {
            $searchComments = $searchComments->where('title', 'like', '%' . $title . '%');
        }
        if (is_numeric($is_active)) {
            $searchComments = $searchComments->where('is_active', $is_active);
        }
        return $searchComments;
    }

    public function replyVideocomments()
    {
        $response = [];
        try {
            $response['data']['reply_list'] = Comment::where('_id', $this->request->id)->first(); 
            $response['error'] = false;
        } catch (\Exception $e) {
            $response['error'] = true;
        }
        return $response;
    }
    /** 
     * Deleting parent and child comments
     *  @param int $comment_id
     */
    public function deleteComment($comment_id)
    { 
        
        try {
            $userid=$this->authUser->id;

            $comment = Comment::where('customer_id', $userid)->where(function($query) use ($comment_id) {
                $query->where('_id', $comment_id)->orWhere('parent_id', $comment_id);
            })->delete();

            return ($comment) ? 1 : 0;
        } catch(\Exception $e) {
              
            return 0;
        }
        
    }
}
