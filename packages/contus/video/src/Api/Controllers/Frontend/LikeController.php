<?php

/**
 * Like Controller
 *
 * To manage the like/dislike functionality.
 *
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2018 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Contus\Base\Controller as BaseController;
use Contus\Video\Repositories\LikeRepository;

class LikeController extends BaseController
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $repository;

    /**
     * Construct method
     */
    public function __construct(LikeRepository $likeRepository)
    {
        parent::__construct();
        $this->repository = $likeRepository;
    }

    /**
     * Post like for videos
     *
     * @return json
     */
    public function postLike()
    {
        $like = $this->repository->postLike();
        if ($like['success']) {
            return $this->getSuccessJsonResponse(['message' => $like['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $like['message']]);
    }

    /**
     * Post dislike for videos
     *
     * @return json
     */
    public function postDisLike()
    {
        $like = $this->repository->postDisLike();
        if ($like['success']) {
            return $this->getSuccessJsonResponse(['message' => $like['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $like['message']]);
    }
}
