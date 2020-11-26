<?php

/**
 * Front Video Repository
 *
 * To manage the functionalities related to videos for the frontend
 *
 * @name FrontVideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */

namespace Contus\Video\Repositories;

use Illuminate\Support\Facades\DB;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Contus\Video\Models\Collection;
use Contus\Video\Models\Group;
use Contus\Notification\Models\Notification;
use Contus\Video\Models\Comment;
use Contus\Video\Models\WatchHistory;
use Contus\Geofencing\Models\GeoIndividualAllowedCountries;
use Carbon\Carbon;
use Contus\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Contus\Video\Models\Season;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\TvodVideoPercentage;
use Contus\Payment\Models\PaymentTransactions;
use Contus\Video\Models\VideoXrayCast;
use Contus\Video\Models\ContinueWatchHistory;
use Contus\Video\Traits\VideoTrait as VideoTrait;
use Contus\Video\Traits\CategoryTrait as CategoryTrait;
use Illuminate\Support\Facades\Log;

class FrontVideoRepository extends VideoRepository{
    use VideoTrait, CategoryTrait;
    
    /**
     * function to get all tags
     *
     * @vendor Contus
     *
     * @package video
     * @return unknown
     */
    public function getallTags()
    {
        if ($this->request->category) {
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), explode(',', $this->request->category))->pluck('id');
        } else {
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), array_keys($this->categoryRepository->getAllCategories($this->request->main_category)))->pluck('id');
        }
        return $this->tag->whereHas('videos.categories', function ($query) use ($categoryId) {
            $query->whereIn('categories.id', $categoryId);
        })->pluck('name', 'id');
    }

    /**
     * Get Live Video Notification lists
     */
    public function getLiveVideoNotification()
    {
        $savedVideos = Video::where('is_archived', 0)->where('is_active', 1)->where('job_status', 'Complete')->where('notification_status', 0)->where('is_live', 0)->orderBy('video_order', 'desc')->get();
        $liveVideos = Video::where('is_archived', 0)->where('is_active', 1)->where('liveStatus', 'ready')->where('job_status', 'Complete')->whereRaw('DATE(scheduledStartTime) = "' . Carbon::now()->tomorrow()->toDateString() . '"')->where('notification_status', 0)->where('is_live', 1)->orderBy('scheduledStartTime', 'asc')->get();
        if ($liveVideos->toArray() || $savedVideos->toArray()) {
            $customer = Customer::where('email', '!=', '')->where('is_active', 1)->where('notify_newsletter', 0)->get();

            $vHtml = $this->formatLiveNotification($savedVideos, 1);
            $LHtml = $this->formatLiveNotification($liveVideos, 2);
            
            $html = '<h2>##NAME##, </h2>' . $vHtml . $LHtml;
            foreach ($customer as $c) {
                $content = str_replace(['##NAME##'], [$c->name], $html);
                $this->email($c, 'New videos in ' . config()->get('settings.general-settings.site-settings.site_name'), $content);
            }
            return true;
        }
        return false;
    }

    /**
     * function to get video with complete information using slug
     *
     * @return unknown
     */
    public function getVideoSlug($slug, $commentList = false)
    {
        if(!empty($this->request->user_id)){
            $user_id = $this->request->user_id;
        }else{
            $user_id = 0;
        }

        if(!empty($this->request->is_newversion)){
            $is_newversion = $this->request->is_newversion;
        }else{
            $is_newversion = '';
        }

       // Log::info('tests'.$user_id);

       // Log::info('testss'.$is_newversion);

        $this->video = new Video();
        $this->video = $this->video->whereCustomer()->where('is_active', 1)->where('videos.' . $this->getKeySlugorId(), $slug);
    
        if (is_null($this->video->first())) {
            $this->throwJsonResponse(false, 404, trans('video::videos.slugResponse'));
        }

        // $fields = 'videos.id, videos.episode_order,videos.title,videos.slug,videos.description, videos.thumbnail_image,videos.video_duration,videos.hls_playlist_url,videos.is_live,videos.director,videos.imdb_rating,videos.releaseYear, videos.scheduledStartTime,videos.published_on,videos.presenter,videos.is_premium, videos.poster_image, videos.view_count, videos.id as is_favourite, videos.id as video_category_name,videos.id as video_category_slug,videos.id as parent_category_slug, videos.id as is_like, videos.id as is_dislike, videos.id as like_count, videos.id as dislike_count, videos.id as auto_play, videos.id as season_name, videos.id as season_id, videos.subtitle,videos.iossubtitles, videos.id as passphrase, videos.created_at, videos.sprite_image, videos.id as ads_url, videos.id as comments_count, videos.price , id as global_video_view_count';
        $fields = 'videos.id, videos.episode_order,videos.title,videos.slug,videos.description, videos.thumbnail_image,videos.video_duration,videos.hls_playlist_url,videos.is_live,videos.director,videos.imdb_rating,videos.releaseYear, videos.presenter,videos.poster_image, videos.id as video_category_name,videos.id as video_category_slug, videos.id as auto_play, videos.id as season_name, videos.id as season_id, videos.subtitle,videos.iossubtitles, videos.created_at, videos.sprite_image,videos.xmltv_id,videos.custom_siteid';

        $this->video = $this->video->selectRaw($fields)->groupBy('videos.id');

        $this->video = ($this->request->header('x-request-type') == 'mobile') ? $this->video->first() : $this->video->with('tags')->first();
        $this->video['is_restricted']=$this->handleVideoGeoFencing($this->video);
        $result = $this->getCurrentDuration($slug, $user_id);
        $this->video['current_duration'] = $result['current_duration'];
        if($is_newversion) {
            $this->video['hls_playlist_url'] = $this->url_encryptor('encrypt', $this->video['hls_playlist_url']);
        }
        $result_percentage = $this->calculatePercentage($this->video['video_duration'], $result['current_duration']);
        $this->video['percentage'] = $result_percentage['percentage'];
        $this->video['episode_title'] = $result['episode_title'];
        $this->video['series_title'] = $result['series_title'];
       // $this->['vinod'] = 200;
        return $this->video;
    }

    /**
     * function to get payment details of the user for a particular video
     *
     * @return unknown
     */
    public function getVideoPaymentInfo($videoId)
    {
        $id = !empty(authUser()->id) ? authUser()->id : 0;

        $getPaymentInfo = PaymentTransactions::select('video_id','status','transaction_id','view_count','global_view_count')->where('customer_id', $id)->where('video_id',$videoId)->where('status','Paid')->where('transaction_id','!=', '')->first();
        if(!empty($getPaymentInfo)){
            $getPaymentDetail['is_bought'] = 1;
            $getPaymentDetail['transaction_id'] = $getPaymentInfo->transaction_id;
            $getPaymentDetail['user_view_count'] = $getPaymentInfo->view_count;
            $getPaymentDetail['global_view_count'] = $getPaymentInfo->global_view_count;
        } else {
            $getPaymentDetail['is_bought'] = 0;
            $getPaymentDetail['transaction_id'] = null;
            $getPaymentDetail['user_view_count'] = null;
            $getPaymentDetail['global_view_count'] = null;
        }
        return $getPaymentDetail;
    }

    /**
     * function to store/get TVOD video complete percentage details of the user for a particular video
     *
     * @return unknown
     */
    public function getVideoPercentageDetail($transaction_id,$complete_percentage)
    {
        $result = 'success';
        $tvodViewPercentage = new TvodVideoPercentage();
        $percentageData = $tvodViewPercentage->where('transaction_id',$transaction_id)->first();
        if(!empty($percentageData->transaction_id)) {
            if($percentageData->complete_percentage == 50 && $complete_percentage == 100) {
                $tvodViewPercentage->where('transaction_id',$transaction_id)->delete();
                $result = 'success';
            } else {
                $result = 'updated';
            }
        } else {
            if($complete_percentage == 50) {
                $tvodCompletePercentageData = [
                    'transaction_id'=>$transaction_id,
                    'complete_percentage'=>$complete_percentage
                ];
                $tvodViewPercentage->fill($tvodCompletePercentageData);
                $tvodViewPercentage->save();
                $result = 'updated';
            } else {
                $result = 'updated';
            }
        }
        return $result;
    }
    /**
     * function to get video with complete information using slug
     *
     * @return unknown
     */
    public function getWatchVideoSlug($slug, $user_id = 0, $is_newversion = 0){
        
        $status = 'unauthorized';
        $response['payMethod'] = 'SVOD';
        $this->video = new Video();
        $this->video = $this->video->whereCustomer()->where('is_active', 1)->where('videos.' . $this->getKeySlugorId(), $slug);
        if (is_null($this->video->first())) {
            $this->throwJsonResponse(false, 404, trans('video::videos.slugResponse'));
        }
        $this->video = $this->video->selectRaw('videos.id, videos.slug, videos.description, videos.hls_playlist_url, videos.is_live, videos.imdb_rating, videos.releaseYear, videos.director, videos.title, videos.presenter,videos.subtitle,videos.iossubtitles, videos.sprite_image, videos.id as season_name, videos.id as season_id, videos.is_premium, videos.episode_order, videos.video_duration')->first();
        //for new version users encrypt the hls_playlist_url
        
        if($is_newversion) {
            $this->video->hls_playlist_url = $this->url_encryptor('encrypt', $this->video->hls_playlist_url);
        }
        $isVideoPremium = $this->video->is_premium;
        $price = $this->video->price;
        $response['videoSlug'] = $this->video->slug;
        $is_bought = $this->getVideoPaymentInfo($this->video->id);
        /** Call to method to check if the user is authorized/premium user to watch the video */
        $isCustomerSubscribed = $this->getIsSubscribedAttribute();
        if(!$isVideoPremium && !$isCustomerSubscribed) {
            if($price > 0 && $is_bought['is_bought'] === 0) {
                $status = 'unauthorized';
                $response['payMethod'] = 'TVOD';
            } else {
                $this->postWatchHistory($this->video);
                $this->addVideoAnalytics($this->video);
                $status = 'authorized'; 
            }
        } else if(($isVideoPremium && $isCustomerSubscribed) || (!$isVideoPremium)){
            $this->postWatchHistory($this->video);
            $this->addVideoAnalytics($this->video);
            $status = 'authorized';
        } else if($isVideoPremium && $is_bought['is_bought'] === 1) {
            $this->postWatchHistory($this->video);
            $this->addVideoAnalytics($this->video);
            $status = 'authorized';
        } else if($price > 0 && $is_bought['is_bought'] === 0 && !$isCustomerSubscribed) {
            $status = 'unauthorized';
            $response['payMethod'] = 'TVOD';
        } else {
            $status = 'authorized';
        }
        $is_restricted = $this->handleVideoGeoFencing($this->video);
        if ($this->video->is_webseries) {
            //$next_episode_slug = $this->handleNextEpisodeSlug($slug);
            //$this->video->next_episode_slug = $next_episode_slug;
        } else {
           // $this->video->next_episode_slug = null;
        }
        $this->video->is_restricted = $is_restricted;
        //::info('user_id'.$user_id);
        $result = $this->getCurrentDuration($slug, $user_id);
        $this->video->current_duration = $result['current_duration'];
        $result_percentage = $this->calculatePercentage($this->video->video_duration, $result['current_duration']);
        $this->video->percentage = $result_percentage['percentage'];
        $this->video->episode_title = $result['episode_title'];
        $this->video->series_title = $result['series_title'];
        return ($status == 'authorized')?['status' => $status,'data'=>$this->video]
                                                 :['status' => $status,'data'=>$response];
    }

    /**
     * function to post video details in tvod_view_count Collection
     *
     * @return unknown
     */
    public function insertTvodViewCount($transaction_id){
        $paymentTransactions = new PaymentTransactions;
            $paymentTransactions->where('transaction_id', $transaction_id)->increment('view_count');
            $data = $paymentTransactions->select('video_id','status','view_count','global_view_count')->where('transaction_id', $transaction_id)->first();
            if($data->view_count >= $data->global_view_count) {
                $paymentTransactions->where('transaction_id', $transaction_id)->update(['status' => 'Expired']);
                $data = $paymentTransactions->select('video_id','status','view_count','global_view_count')->where('transaction_id', $transaction_id)->first();
            }
        
    return $data;        
    }
    /**
     * function to post video details in tvod_view_count Collection
     *
     * @return unknown
     */
    public function getTransactionDetails($transaction_id){
        $result = PaymentTransactions::select('video_id','status')->where('transaction_id',$transaction_id)->where('status','Paid')->first();
        $result = !empty($result->video_id)?'success':'failure';
        return $result;
    }
    /**
     * function to post video details in watch history
     *
     * @return unknown
     */
    public function postWatchHistory($video){
        if($video){
            $video->increment('view_count');
            $ip = getIPAddress();
            if (!empty(authUser()->id)) {                
                $this->watchHistory = $this->watchHistory->where ( 'video_id',  $video->id )->where('customer_id',  authUser()->id)->first();
            } else {
               $this->watchHistory = $this->watchHistory->where ( 'video_id',  $video->id )->where('ip_address',  $ip)->first();
            }
            if (is_object($this->watchHistory) && !empty($this->watchHistory->id)) { 
                $this->watchHistory->is_active = 1;
                $this->watchHistory->updated_at = Carbon::now()->toDateTimeString();
                $this->watchHistory->save();
            } else {
                $watchHistory = new WatchHistory();
                $watchHistory->video_id = $video->id;
                $watchHistory->customer_id = (!empty(authUser()->id)) ? authUser()->id : '';
                $watchHistory->ip_address = (!empty(authUser()->id)) ? '' : $ip;
                $watchHistory->is_active = 1;
                $watchHistory->save();
            }
        }
    }
    /**
     * Method to handle geofencing for videos
     * 
     * @param array $video
     * return boolean
     */
    public function handleVideoGeoFencing($video){
        if($video){
            $videoId = (string)$video->id;
            $is_restricted = 0;
            $allowedRegions = array();
            $geoSettings = $this->geoSettings->select('type')->where('is_active',1)->first();
            $geoSettingsType = $geoSettings->type;
            if(!empty($geoSettingsType) && $geoSettingsType != 'all_countries'){
                $userIPAddress = $_SERVER['REMOTE_ADDR'];
                $geoData = geoip($userIPAddress);
                $countryCode = $geoData['iso_code'];
                $regionCode = $geoData['state'];
                if($countryCode === 'NA'){
                    $is_restricted = 1;
                } else {
                    $geoModel = ($geoSettingsType == 'global_allowed_countries') ? $this->geoGlobalAllowedCountries
                                : $this->geoIndividualAllowedCountries->where('video_id','=',$videoId);
                    $geoCollection = $geoModel->get();
                    $allowedRegionsData = $geoModel->where('country_short_code','=', (string)$countryCode)->pluck('regions')->toArray();
                    $allowedRegions = $this->convertRegionsintoArray($allowedRegionsData);
                    if((count($geoCollection) > 0) && !in_array($regionCode, $allowedRegions, true)){
                        $is_restricted = 1;
                    }
                }
            }
            return $is_restricted;
        }
    }
    /**
     * Method to get next episode url if its a webseries video
     */
    public function handleNextEpisodeSlug($slug){
        $this->video = new Video();
        $this->video = $this->video->whereCustomer()->where('is_active', 1)->where('videos.' . $this->getKeySlugorId(), $slug);
        $fields = 'videos.id, videos.episode_order, videos.title,videos.slug,videos.description, videos.thumbnail_image,videos.video_duration,videos.hls_playlist_url,videos.is_live,videos.imdb_rating, videos.releaseYear, videos.director,videos.scheduledStartTime,videos.published_on,videos.presenter,videos.is_premium, videos.poster_image, videos.view_count, videos.id as is_favourite, videos.id as video_category_name, videos.id as is_like, videos.id as is_dislike, videos.id as like_count, videos.id as dislike_count, videos.id as auto_play, videos.id as season_name, videos.id as season_id, videos.subtitle,videos.iossubtitles, videos.id as passphrase, videos.created_at, videos.sprite_image, videos.id as ads_url, videos.id as comments_count, videos.price , id as global_video_view_count';
        $this->video = $this->video->selectRaw($fields)->groupBy('videos.id')->first();
        $category = '';
        $episodes = [];
        $seasons = [];
        $current_key;
        $current_season_key;
        $season = $this->video->season_id;
        $seasons = $this->getSeasons($this->video);
        $episodes = $this->getEpisodes($this->video, $season)->toArray();
        $current_key = array_search($this->video->slug, array_column($episodes, 'slug')) + 1;
        if (array_key_exists($current_key, $episodes)) {
            return $this->video->where($this->getKeySlugorId(), $episodes[$current_key][$this->getKeySlugorId()])->first();
        } else {
            $episodes = [];
            $current_key = null;
            $current_season_key = array_search($season, array_column($seasons, 'id')) + 1;
            if (array_key_exists($current_season_key, $seasons)) {
                $seasonId =  $seasons[$current_season_key]['id'];
                $episodes = $this->getEpisodes($this->video, $seasonId)->toArray();
                $current_key = 0;
                if (array_key_exists($current_key, $episodes)) {
                    return $this->video->where($this->getKeySlugorId(), $episodes[$current_key][$this->getKeySlugorId()])->first();;
                }
            } else {
                return null;
            }
        }
    }

    /**
     * Method get Episodes
     */
    public function getEpisodes($video, $season) {
        if(!empty($video->categories()->first())) {
            $category = $video->categories()->first()->id;
        }
        $this->videoInstance = new Video();
        $this->videoInstance = $this->videoInstance->whereCustomer()->where('is_active', 1);
        $this->videoInstance = $this->videoInstance->whereHas('season', function ($query) use ($season) {
            $query->where('season_id', $season);
        })->whereHas('categories', function($query) use ($category) {
            $query->where('categories.id', $category);
        })->selectRaw('videos.id, videos.episode_order, videos.slug')->groupBy('videos.id')->orderBy('videos.episode_order', 'asc');
        return $this->videoInstance->get();
    }

    /**
     * function to get comments for video using slug
     *
     * @return unknown
     */
    public function getCommentsVideoSlug($slug, $getCount = 10, $paginate = true)
    {
        $inputArray = $this->request->all();
        if(!empty($inputArray['parent_id'])) {
            $commentList = Comment::with(['customer' => function($query) {
                $query->withTrashed();
            }, 'admin'])->where('_id', $inputArray['parent_id'])->orderBy('_id', 'desc')->paginate(config('access.perpage'));
        }
        else {
            $video          = Video::where($this->getKeySlugorId(), $slug)->first();
            $commentList    = Comment::with(['customer' => function($query) {
                $query->withTrashed();
            }, 'admin'])->where('video_id', $video->id)->whereNull('parent_id')->orderBy('_id', 'desc')->paginate(config('access.perpage'));
        }
        return $commentList;
    }

    /**
     * function to get live related videos
     *
     * @return object
     */
    public function getLiverelatedVideos($slug)
    {
        return $this->video->whereliveVideo()->where($this->getKeySlugorId(), '!=', $slug)->orderBy('scheduledStartTime', 'desc')->paginate(10, ['videos.id', 'videos.title', 'videos.slug', 'videos.thumbnail_image', 'videos.is_live']);
    }

    /**
     * function to get scheduled as well as upcomming live video lists
     *
     * @return array
     */
    public function getAllLiveVideos()
    {
        $videos = $this->video->whereallliveVideo()->orderBy('scheduledStartTime', 'ASC')->get()->toArray();
        return ['data' => $videos, 'next_page_url' => null, 'total' => count($videos)];
    }

    /**
     * function to get recorded live videos
     *
     * @return object
     */
    public function getrecordedLiveVideos($record = '', $getCount = 9, $paginate = true)
    {
        if ($record) {
            $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc')->get();
        } else {
            if ($this->request->header('x-request-type') == 'mobile') {
                $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc')->take(5)->get();
            } else {
                $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc');
                if ($paginate) {
                    $videos = $videos->paginate($getCount)->toArray();
                } else {
                    $videos = $videos->take($getCount)->get();
                }
            }
        }
        return $videos;
    }

    /**
     * Update live stream details
     *
     * @return object
     */
    public function getLiveTime()
    {
        return Video::where('is_active', '1')->where('liveStatus', '!=', 'complete')->where('scheduledStartTime', '!=', '')->where('is_archived', 0)->where('is_live', 1)->select('scheduledStartTime')->first()->orderBy('scheduledStartTime', 'desc');
    }

    /**
     * function to get live videos for widget display
     *
     * @return object
     */
    public function getOnlyLiveVideos($record = '')
    {
        $videos = new Video();
        $serverTime = new \DateTime(date("Y-m-d H:i:s", time()));
        $videos = $videos->whereliveVideo()->orderBy('scheduledStartTime', 'asc') ;
        if ($record) {
            $liverecord = $videos->take($record)->get()->makeHidden('liveStatus')->toArray();
            foreach ($liverecord as $key => $value) {
                $checklivetime = new \DateTime($value ['scheduledStartTime']);
                $liverecord [$key] ['liveVideoTime'] = ($checklivetime <= $serverTime);
            }
        } else {
            $liverecord = $videos->take(4)->get()->makeHidden('liveStatus');
        }
        return $liverecord;
    }

    /**
     * function to get recent videos for video using slug
     *
     * @return array
     */
    public function getVideoByType($type)
    {
        $userId = (!empty(authUser()->id)) ? authUser()->id : 0;
        $video = $this->video->whereCustomer();
        if ($type == 'banner') {
            $video = $video->leftJoin('favourite_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', $userId);
            })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('is_live', '==', 0)->orderBy('id', 'desc')->take(5)->get();
        } elseif ($type == 'recent') {
            $video = $this->video->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->leftJoin('recently_viewed_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id');
            })->where('f1.customer_id', '=', $userId)->selectRaw('videos.*')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('is_live', '==', 0)->orderBy('id', 'desc')->take(4)->get();
            foreach ($video as $k => $v) {
                $video [$k] ['is_favourite'] = $v->authfavourites()->get()->count();
            }
            if (!count($video) > 0) {
                $video = $this->video->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('trailer_status', 1)->leftJoin('favourite_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', $userId);
                })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('is_live', '==', 0)->orderBy('id', 'desc')->take(4)->get();
            }
        } elseif ($type == 'trending') {
            $video = $video->join('recently_viewed_videos', 'videos.id', '=', 'recently_viewed_videos.video_id')->where('recently_viewed_videos.created_at', '>', Carbon::now()->subDays(30))->selectRaw('videos.*,count("video_id") as count')->groupBy('recently_viewed_videos.video_id')->where('is_live', '==', 0)->orderBy('count', 'desc')->take(10)->get();
            foreach ($video as $k => $v) {
                $video [$k] ['is_favourite'] = $v->authfavourites()->get()->count();
            }
        }
        return $video;
    }

    /**
     * function to get upcomming live videos
     *
     * @return mixed
     */
    public function getLiveVideos($live = '', $getCount = 9, $paginate = true)
    {
        if ($live) {
            $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc')->get();
        } else {
            if ($this->request->header('x-request-type') == 'mobile') {
                $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc')->take(5)->get();
            } else {
                $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc');
                if ($paginate) {
                    $videos = $videos->paginate($getCount)->toArray();
                } else {
                    $videos = $videos->take($getCount)->get();
                }
            }
        }
        return $videos;
    }

    /**
     * function to get recent videos for video using slug
     *
     * @return array
     */
    public function getVideoBlockByType($type, $search='')
    {
        $video = $this->video->whereCustomer();

        $fields = 'videos.id, videos.title, videos.description, videos.slug, videos.thumbnail_image, videos.poster_image, videos.is_live,videos.imdb_rating, videos.releaseYear, videos.director,videos.view_count';

        return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups','collections_videos', 'video_categories', 'watch_history'])->remember(getCacheKey(1).'_home_page_info_'.$type, getCacheTime(), function () use($type, $fields, $video, $search) {
            switch ($type) {
                case $type == 'banner':
                    $fields = $fields.' , videos.description, videos.poster_image ';
                    $video = $this->fetchBannerVideos($fields, $this->video->where('videos.is_live', 0));
                    $video = $this->formatResponse('', $fields, $video, $type);
                    break;
                case $type == 'recent':
                    $video = $this->fetchRecentVideos($fields, $this->video);
                    $video = $this->formatResponse('', $fields, $video, $type);
                    break;
                case $type == 'trending':
                    $video = $this->getTrendingVideos([]);
                    $video = $this->formatResponse('', $fields, $video, $type);
                    break;
                case $type == 'section_one':
                    $nthCategory    = $this->getTopNthCategory();
                    $video = $this->formatResponse($nthCategory, $fields, $video, $type, $search);
                    break;
                case $type == 'section_two':
                    $nthCategory    = $this->getTopNthCategory(1);
                    $video = $this->formatResponse($nthCategory, $fields, $video, $type, $search);
                    break;
                case $type == 'section_three':
                    $nthCategory    = $this->getTopNthCategory(2);
                    $video = $this->formatResponse($nthCategory, $fields, $video, $type, $search);
                    break;
                default:
                    $video = $this->fetchNewVideos($fields, $video);
                    $video = $video->toArray();
                    $video = $this->formatResponse('', $fields, $video, $type);
                    break;
            }

            return $video;
        });


        
    }
    public function formatResponse($nthCategory, $fields, $video, $type, $search='')
    {
        if(in_array(strtolower($type), ['section_one', 'section_two', 'section_three'])) {
            $categoryArray          = $this->fetchChildren($nthCategory);
            $video                  = $this->fetchPopularVideos($video, $categoryArray, $fields, $search);
            $video['category_name'] = (!empty($nthCategory)) ? trans('video::videos.popular_in').' '. $nthCategory->title : '';
            $video['category_slug'] = (!empty($nthCategory)) ? $nthCategory->slug : '';
        }
        else {
            if($type == 'banner') {
                $video['category_name'] = trans('general.banner_videos');
            }
            else if($type == 'recent') {
                $video['category_name'] = trans('general.recent_videos');
            }
            else if($type == 'trending') {
                $video['category_name'] = trans('general.trending_videos');
            }
            else {
                $video['category_name'] = trans('general.new_videos');
            }
            $video['category_slug'] = (!empty($nthCategory)) ? $nthCategory->slug : '';
        }
        $video['type'] = $type;
        return $video;
    }
    public function fetchChildren($category)
    {
        
        $catId = !empty($category['id']) ? $category['id'] : 0;
        return app('cache')->tags([getCacheTag(), 'categories'])->remember(getCacheKey().'_nth_category_'.$catId, getCacheTime(), function () use($catId) {
            $categoryArray = [];
            $categoryInfo = $this->category->with(['child_category'])->where('id', $catId)->first();
            if (!empty($categoryInfo)) {
                if (isset($categoryInfo['child_category'])) {
                    foreach ($categoryInfo['child_category'] as $cat) {
                        $cat = $cat->makeVisible(['id']);
                        $categoryArray[$cat->id] = $cat->id;
                    }
                }
                $categoryArray[] = $categoryInfo->id;
            }
            return $categoryArray;
        });

        
    }

    public function fetchPopularVideos($video, $categoryArray, $fields = '',$search ='')
    {
        $tempArray = (!is_array($categoryArray)) ? $categoryArray->toArray() : $categoryArray;
        $key = 'category'.(!empty($tempArray)) ? implode('#',$tempArray) : '0';

        return app('cache')->tags([getCacheTag(), 'videos', 'categories','video_categories', 'groups', 'collections_videos'])->remember(getCacheKey().$key.'_popular_videos', getCacheTime(), function () use($video, $categoryArray, $fields) {
            $inputArray = $this->request->all();
            if(isset($inputArray['perpage']) && !empty($inputArray['perpage'])) {
                $perPage = $inputArray['perpage'];
            }else {
                $perPage = 20;
            }
            $search  = !empty($inputArray['search']) ? str_replace("%20", ' ', $inputArray['search']) : '';
            if($fields == '') {
                $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';
            }

            if(!empty($search)){
                $result = $video->leftjoin('video_categories as vc', 'videos.id', '=', 'vc.video_id')
                    ->selectRaw($fields)->where('is_live', 0)->where('videos.title', 'like', '%' . $search . '%')->whereIn('vc.category_id', $categoryArray);
            }else{
                $result = $video->leftjoin('video_categories as vc', 'videos.id', '=', 'vc.video_id')
                ->selectRaw($fields)->where('is_live', 0)->whereIn('vc.category_id', $categoryArray);
            }

            
            $result = $result->where('is_adult',0);
                        
            if(!empty($inputArray['is_web_series'])) {
                $condition      = '';
                $categoryString = (!empty($categoryArray)) ? implode(',', $categoryArray->toArray()) : '';
                if($categoryString != '') {
                    $condition = ' and vc2.category_id in ('.$categoryString.')';
                }

                $sql ='(select v2.id, max(v2.view_count) as maxView, vc2.category_id 
                                from videos as v2 
                                left join video_categories as vc2 on (vc2.video_id = v2.id) 
                                where v2.is_active = 1 and v2.is_archived = 0 and v2.job_status = "Complete" '.$condition.' group by  vc2.category_id) as sub ';

                $myfinal = Video::selectRaw($fields)
                    ->leftjoin('video_categories as vc1', 'vc1.video_id', '=', 'videos.id')
                    ->join(\DB::raw($sql) , function ($query) {
                                $query->on('sub.maxView','=','videos.view_count');
                                $query->on('sub.category_id', '=','vc1.category_id');
                            })->whereIn('vc1.category_id', $categoryArray)->where('videos.is_live', 0)->where('videos.is_active', 1)->where('videos.job_status', 'Complete')->where('videos.is_archived',0);
                
                if(!empty($search)){
                    $myfinal->where('videos.title', 'like', '%' . $search . '%');
                }
                
                $myfinal = $myfinal->where('is_adult',0);
                
                
                $result = $myfinal->orderBy('videos.view_count', 'desc');
            }
            else {
                $result = $result->groupBy('videos.id')->orderBy('videos.view_count', 'desc');
            }
            
            return $result->paginate($perPage)->toArray();
        });

        
    }
   

    /**
     * Function to fetch new videos
     * @param  [string] $fields - sql fields
     * @param  [object] $video - Vreturn app('cache')->tags([getCacheTag(), 'videos', 'categories','video_categories'])->remember(getCacheKey().'_popular_videos', getCacheTime(), function () use($video, $categoryArray, $fields) {ideo object
     * @return object
     */
    public function fetchNewVideos($fields, $video, $categoryArray = [], $search = '')
    {
        $order = 'created_at';
        $sort = 'desc';
        
        $inputArray = $this->request->all();
        $key = getCacheKey();
        if (isset($inputArray['order']) && !empty($inputArray['order'])) {
            $sort  = (!empty($inputArray['sort'])) ? $inputArray['sort'] : 'asc';
            $order = $inputArray['order'];
        }

        if(isset($inputArray['perpage']) && !empty($inputArray['perpage'])) {
            $perPage = $inputArray['perpage'];
        }else {
            $perPage = 50;
        }

        $key .= '_order'.$order.'_sort'.$sort;

        $tempArray = (!is_array($categoryArray)) ? $categoryArray->toArray() : $categoryArray;
        $key .= '_category'.(!empty($tempArray)) ? implode('#',$tempArray) : '0';

        return app('cache')->tags([getCacheTag(),'videos','categories', 'groups', 'video_categories', 'collections_videos'])->remember($key.'_new_videos', getCacheTime(), function () use($fields, $video, $categoryArray, $order, $sort, $inputArray, $search, $perPage) {

            if(!empty($search)){
                $info = $video->selectRaw($fields)->where('videos.title', 'like', '%' . $search . '%')->where('is_live', '==', 0)->where('is_webseries', 0);
            } else{
                //$info = $video->selectRaw($fields)->where('is_live', '==', 0)->where('is_webseries', 0)->whereDate('created_at', '>', Carbon::now()->subDays(30));
               $info = $video->selectRaw($fields)->where('is_live', '==', 0)->where('is_webseries', 0);
            }

            $info = $info->where('is_adult',0);

            if(!empty($categoryArray)) {
                $info->whereHas('categories', function($query) use ($categoryArray){
                     $query->whereIn('categories.id', $categoryArray);
                });
            }

            if(!empty($inputArray['is_web_series'])) {
                $info = $info->leftjoin('video_categories as vc', 'videos.id', '=', 'vc.video_id')->groupBy('vc.category_id')->orderBy($order, $sort)->paginate($perPage)->toArray();
            }
            else {
                $info = $info->groupBy('videos.id')->orderBy($order, $sort)->paginate($perPage)->toArray();
            }

            // $currentPage = (!empty($inputArray['page'])) ? $inputArray['page'] : 1;
            // //$perPage = 20;//config('access.perpage');
            // if ($currentPage == 1) {
            //     $start = 0;
            // } else {
            //     $start = ($currentPage - 1) * $perPage;
            // }

            // $currentPageCollection = array_slice($info, $start, $perPage);
            // $paginatedTop100 = new LengthAwarePaginator(array_values($currentPageCollection), count($info), $perPage);
            // $paginatedTop100->setPath(LengthAwarePaginator::resolveCurrentPath());
            //return $paginatedTop100;
            return $info;

        });
    }

    /**
     * Function to fetch popular genre videos
     */
    public function fetchPopularGenre($paginate = true)
    {
        return app('cache')->tags([getCacheTag(), 'videos', 'categories', 'groups','collections_videos', 'video_categories'])->remember(getCacheKey().'_popular_genre', getCacheTime(), function () use($paginate) {
            $this->setRules(['order' => 'sometimes|in:title', 'sort' => 'sometimes|in:asc,desc']);
            $this->validate($this->request, $this->getRules());

            $inputArray = $this->request->all();
            $collectionObj = new Group();
            $collection = $collectionObj
                ->join('collections_videos', 'collections_videos.group_id', '=', 'groups.id')
                ->join('videos', 'videos.id', '=', 'collections_videos.video_id')
                ->selectRaw('groups.*, count("collections_videos.id") as video_count')
                ->where('groups.is_active', 1)
                ->where('videos.is_active', 1)->where('videos.job_status', 'Complete')->where('videos.is_archived', 0);
            if (isset($inputArray['order']) && !empty($inputArray['order'])) {
                $sortName = ($inputArray['order'] == 'title') ? 'name' : $inputArray['order'];
                $collection = $collection->orderBy($sortName, $inputArray['sort']);
            } else {
                $collection = $collection->orderBy('video_count', 'desc');
            }

            $collection = $collection->groupBy('groups.id');
            $collection = ($paginate) ? $collection->paginate(config('access.perpage')) : $collection->get();
            $collection = $collection->toArray();
            $collection['category_name'] = trans('general.genre_videos');
            return $collection;
        });
    }

    /**
     * Function to fetch All videos (vod) -- vinod
     * @param  [string] $fields - sql fields
     * @param  [object] $video - Vreturn app('cache')->tags([getCacheTag(), 'videos', 'categories','video_categories'])->remember(getCacheKey().'_popular_videos', getCacheTime(), function () use($video, $categoryArray, $fields) {ideo object
     * @return object
     */
    public function fetchAllVideos($fields, $video, $categoryArray = [], $search = '')
    {
        $order = 'created_at';
        $sort = 'desc';
        $inputArray = $this->request->all();
        $key = getCacheKey();
        if (isset($inputArray['order']) && !empty($inputArray['order'])) {
            $sort  = (!empty($inputArray['sort'])) ? $inputArray['sort'] : 'asc';
            $order = $inputArray['order'];
        }

        if(isset($inputArray['perpage']) && !empty($inputArray['perpage'])) {
            $perPage = $inputArray['perpage'];
        }else {
            $perPage = 50;
        }

        $key .= '_order'.$order.'_sort'.$sort;

        $tempArray = (!is_array($categoryArray)) ? $categoryArray->toArray() : $categoryArray;
        $key .= '_category'.(!empty($tempArray)) ? implode('#',$tempArray) : '0';
        //$key.'_new_videos', getCacheTime(), 
        //
        return app('cache')->tags([getCacheTag(),'videos','categories', 'groups', 'video_categories', 'collections_videos'])->remember( getCacheKey().$key.'_popular_videos', getCacheTime(), function () use($fields, $video, $categoryArray, $order, $sort, $inputArray, $search, $perPage) {

            if(!empty($search)){
                $info = $video->selectRaw($fields)->where('videos.title', 'like', '%' . $search . '%')->where('is_live', '==', 0)->where('is_webseries', 0);
            } else{
                $info = $video->selectRaw($fields)->where('is_live', '==', 0)->where('is_webseries', 0);
            }

            $info = $info->where('is_adult',0);
            
            
            if(!empty($categoryArray)) {
                $info->whereHas('categories', function($query) use ($categoryArray){
                     $query->whereIn('categories.id', $categoryArray);
                });
            }

            if(!empty($inputArray['is_web_series'])) {
                $info = $info->leftjoin('video_categories as vc', 'videos.id', '=', 'vc.video_id')->groupBy('vc.category_id')->orderBy($order, $sort)->paginate($perPage)->toArray();
            }
            else {
                $info = $info->groupBy('videos.id')->orderBy($order, $sort)->paginate($perPage)->toArray();
            }

            return $info;

        });
    }

    /**
     * Function to load more videos in homescreen
     */
    public function getMore()
    {
        $result['error']    = false;
        $result['message']  = '';
        $result['data']  = '';
        $this->setRules(['type' => 'required|in:new,recent,section_one,section_two,section_three,banner,trending,genre']);
        $this->validate($this->request, $this->getRules());
        try {
            if ($this->request->type == 'genre') {
                $result['data'] = $this->fetchPopularGenre();
            } else {
                $result['data'] = $this->getVideoBlockByType($this->request->type);
            }
        } catch (\Exception $e) {
            $result['error']    = true;
            $result['message']    = $e->getMessage();
        }
        return $result;
    }


        /**
     * function to get all tags
     *
     * @vendor Contus
     *
     * @package video
     * @return unknown
     */
    public function getVideoCast()
    {
        $videoDetails = Video::where($this->getKeySlugorId(), $this->request->slug)
        ->select('id')
        ->with('castInfo')
        ->first();
       return $videoDetails;
    }
    /**
    * function to get video Id
    *
    * @package video
     */
    public function getVideoId($slug)
    {
        $videoDetails = DB::table('videos')->where('slug', $slug)->select('id', 'slug')->first();
        return $videoDetails;
    }

    /**
    * function to get current video duration 
    *
    * @package video
     */
    public function getCurrentDuration($slug, $user_id) {
        $videoDetails = $this->getVideoId($slug);
        if(!empty($videoDetails)){
            $video_id = $videoDetails->id;
            //Log::info('video'.$video_id.'----'.$user_id);
            $video_history = ContinueWatchHistory::where(['video_id' => $video_id, 'user_id' => $user_id])->select('duration','title')->first();
            if(!empty($video_history)){
               return array('current_duration' => (int)$video_history['duration'], 'series_title' => $video_history['series_title'],'episode_title' => $video_history['title']);
            }else{
                return array('current_duration' => 0, 'series_title' => '','episode_title' => '');
            }
        }
    }

    public function calculatePercentage($total_duration, $current_duration= 0) {
        $flag = 0; 
        $duration = explode(':',$total_duration);
        if(isset($duration[0])) $tot_duration = ($duration[0]*60*60);
        if(isset($duration[1])) $tot_duration += ($duration[1]*60);
        if(isset($duration[2])) $tot_duration += ($duration[2]);
        if($tot_duration == 0) {
            return array('percentage' => 0, 'flag' => 0);
        }else{
            $percentage = (int)(($current_duration/$tot_duration)*100);
            $flag = ($percentage < 95) ? 1 : 0;
            return array('percentage' => $percentage, 'flag' => $flag);
        }
        
      }

      //encrypt hls video url
    public function url_encryptor($action, $string) {
        $key = "BestBoxVplayed20";
        return base64_encode(openssl_encrypt($string, "aes-128-ecb", $key, OPENSSL_RAW_DATA));
        // $output = false;
  
        // $encrypt_method = "AES-256-CBC";
        // //pls set your unique hashing key
        // $secret_key = 'BestBOX_Vplayed';
        // $secret_iv = 'uandme';
  
        // // hash
        // $key = hash('sha256', $secret_key);
  
        // // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        // $iv = substr(hash('sha256', $secret_iv), 0, 16);
  
        // //do the encyption given text/string/number
        // if( $action == 'encrypt' ) {
        //     $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        //     $output = base64_encode($output);
        // }
        // else if( $action == 'decrypt' ){
        //   //decrypt the given text/string/number
        //     $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        // }
  
        // return $output;
      }


}
