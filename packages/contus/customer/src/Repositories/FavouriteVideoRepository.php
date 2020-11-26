<?php

/**
 * Favourite Video Repository
 *
 * To manage the functionalities related to the Customer module from Latest News Resource Controller
 *
 * @name LatestNewsRepository
 * @vendor Contus
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Customer\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\Video;
use Contus\Video\Models\FavouriteVideo;
class FavouriteVideoRepository extends BaseRepository
{
/**
     * Class property to hold the key which hold the Favourite Video object
     *
     * @var object
     */
    protected $_favouriteVideo;

    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package FavouriteVideo
     *
     * @param Contus\FavouriteVideo\Models\FavouriteVideo $favouriteVideos
     */
    public function __construct(FavouriteVideo $favouriteVideos)
    {
        parent::__construct();
        $this->_favouriteVideo = $favouriteVideos;
    }

    /**
     * Store a newly created Favourite Video or update the Favourite Video.
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @param $video_id input
     *
     * @return boolean
     */
    public function addFavouriteVideos()
    {
        $this->setRules(['video_slug' => 'required']);
        if ($this->_validate()) {
            $date = $this->_favouriteVideo->freshTimestamp();
            $video_slug = $this->request->video_slug;
            $selectedVideoID = Video::where($this->getKeySlugorId(), $video_slug)->value('id');
            if ($selectedVideoID) {
                try {               
                    $existingvideo = $this->_favouriteVideo->where('customer_id',authUser()->id)->where('video_id',$selectedVideoID)->first();               
                    if($existingvideo)
                    {
                     $favorite = $this->_favouriteVideo->where('customer_id',authUser()->id)->where('video_id',$selectedVideoID)->first();
                    } else {
                     $favorite =   $this->_favouriteVideo; 
                    }  
                    $favorite->customer_id = auth()->user()->id;               
                    $favorite->video_id = $selectedVideoID;
                    $favorite->created_at = $date;
                    $favorite->save();
                return true; 
                
                }
                catch (\Exception $e)
                {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Get all Favourite Videos for a customer
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @return array
     */
    public function getAllFavouriteVideos()
    {
        if(!empty(authUser()->id)) {
            $favorite=Video::with(['categories','videoTranslation','collections'])->whereHas('favouriteVideo' , function($query) {
                $query->where('customer_id',auth()->user()->id);
               

             })->paginate(10);
         
            return $favorite->toArray();
        }
        return [];
    }

    /**
     * Get Total count for Favourite Videos of a customer
     *
     * @return array
     */
    public function getFavouriteVideosCount()
    {
        return (!empty(authUser()->id))? authUser()->favourites()->get()->count() : 0;
    }

    /**
     * Delete one Favourite Video using ID
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @param int $video_id
     *
     * @return boolean
     */
    public function deleteFavouriteVideo()
    {
        $this->setRules(['video_slug' => 'required']);
        if ($this->_validate()) {
            $video_slug = $this->request->video_slug;
            $selectedVideoID = Video::where($this->getKeySlugorId(), $video_slug)->value('id');
           
            if ((!empty(authUser()->id))&&($selectedVideoID)) {               
                $favorite = $this->_favouriteVideo->where('customer_id',authUser()->id)->where('video_id',$selectedVideoID)->delete();               
               
                return true;
            } else {
                return false;
            }
        }
    }
}