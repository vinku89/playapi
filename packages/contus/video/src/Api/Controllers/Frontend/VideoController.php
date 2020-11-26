<?php

/**
 * Video Controller
 *
 * To manage the Video such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 *
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Carbon\Carbon;
use Contus\Cms\Models\Banner;
use Contus\Cms\Models\LatestNews;
use Contus\Cms\Repositories\BannerRepository;
use Contus\Cms\Repositories\TestimonialRepository;
use Contus\Customer\Models\Customer;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Customer\Repositories\CustomerRepository;
use Contus\Customer\Repositories\FavouriteVideoRepository;
use Contus\Customer\Repositories\RecentlyViewedVideoRepository;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Notification\Models\Notification;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\Comment;
use Contus\Video\Models\Question;
use Contus\Video\Models\Group;
use Contus\Video\Models\Option;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Video\Repositories\DashboardRepository;
use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Video\Repositories\PlaylistRepository;
use Contus\Video\Repositories\CommentsRepository;
use Contus\Video\Repositories\QuestionsRepository;
use Illuminate\Support\Facades\DB;
//use Log;
use Illuminate\Support\Facades\Log;
use Location;

class VideoController extends CustomVideoController
{
    public $awsRepository;
    /**
     * constructor funtion for video controller
     *
     * @param FrontVideoRepository $videosRepository
     * @param CustomerRepository $customerrepositary
     * @param CategoryRepository $categoryRepository
     * @param SubscriptionRepository $subscriptionRepository
     * @param TestimonialRepository $testimonialrepositary
     * @param PlaylistRepository $playlist
     * @param FavouriteVideoRepository $favourties
     */
    public function __construct(FrontVideoRepository $videosRepository, CustomerRepository $customerrepositary, CategoryRepository $categoryRepository, SubscriptionRepository $subscriptionRepository, TestimonialRepository $testimonialrepositary, PlaylistRepository $playlist, FavouriteVideoRepository $favourties)
    {
        parent::__construct();
        $this->repository = $videosRepository;
        $this->category = $categoryRepository;
        $this->subscription = $subscriptionRepository;
        $this->testimonial = $testimonialrepositary;
        $this->playlist = $playlist;
        $this->notification = new NotificationRepository(new Notification(), new Customer());
        $this->favouritevideos = $favourties;
        $this->customerrepositary = $customerrepositary;
        $this->homebanner = new BannerRepository(new Banner());
        $this->commentRepository = new CommentsRepository(new Comment(), $this->notification);
        $this->questionRepository = new QuestionsRepository(new Question(), $this->notification);
        $this->dashboardrepositary = new DashboardRepository(new Video(), new VideoPreset(), new Option(), new Category(), new Customer(), new Comment());
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());

        $this->repoArray = ['repository', 'category', 'subscription', 'testimonial', 'playlist', 'notification', 'favouritevideos', 'customerrepositary', 'homebanner', 'dashboardrepositary', 'awsRepository', 'commentRepository', 'questionRepository'];
    }

    /**
     * Function to send all video list, category list, tag list
     *
     * @return json
     */
    public function VideoCastDetails()
    {
        $result = $this->repository->searchAllVideo();
        if (isset($result['error']) && $result['error']) {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        } else {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result['data']]);
        }
    }



    /**
     * Function to send all category list
     *
     * @return json
     */
    public function browseAllCategoryVideos()
    {
        $trending = new RecentlyViewedVideoRepository();
        $fetch['categories'] = $this->getCacheData('dashboard_categories', $this->category, 'getAllCategoriesSlugs');
        $fetch['exams'] = $this->getCacheData('dashboard_exams', $this->category, 'getAllExamsByCategories');
        $fetch['live'] = $this->repository->getOnlyLiveVideos(4);
        if (\Auth::user()) {
            $fetch['profileInfo'] = $this->customerrepositary->getProfile();
        } else {
            $fetch['profileInfo'] = [];
            $fetch['notificationCount'] = 0;
        }
        $fetch['trending'] = $this->getCacheData('dashboard_trending', $trending, 'TrendingVideos');
        $fetch['banner_image'] = $this->getCacheData('dashboard_banner_image', $this->homebanner, 'getBannerImage');
        $fetch['testimonials'] = $this->getCacheData('dashboard_testimonials', $this->testimonial, 'getAllTestimonials');
        $fetch['total_number_of_active_customer'] = $this->getCacheData('dashboard_customer_count', $this->dashboardrepositary, 'getCustomersCountData', 'activecustomer');
        $fetch['total_number_of_active_videos'] = $this->getCacheData('dashboard_video_count', $this->dashboardrepositary, 'getVideDocumentCount', 'active');
        $fetch['total_number_of_active_pdfdocs'] = $this->getCacheData('dashboard_pdf_count', $this->dashboardrepositary, 'getVideDocumentCount', 'pdf');
        $fetch['total_number_of_active_audio'] = $this->getCacheData('dashboard_audio_count', $this->dashboardrepositary, 'getVideDocumentCount', 'audio');
        $fetch['latestnews'] = LatestNews::where('is_active', 1)->orderBy('id', 'desc')->take(6)->get();
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    /**
     * Function to fetch all videos
     *
     * @return json
     */
    public function fetchPageAll()
    {
        if ($this->request->has('type') && $this->request->type == 'trending') {
            $trending = new RecentlyViewedVideoRepository();
            $fetch['trending'] = $trending->TrendingVideos();
        } elseif ($this->request->has('type') && $this->request->type == 'exam') {
            $fetch['exams'] = $this->category->getAllExamsByCategories();
        }
        return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
    }

    /**
     * This Function used to get particular videos (upcomming and recorded live) list
     *
     * @return json
     */
    public function getLiveVideos()
    {
        if ($this->request->has('type')) {
            if ($this->request->type == 'live_videos') {
                $fetch['upcoming_live_videos'] = $this->repository->getLiveVideos($this->request->type);
            } else {
                $fetch['recorded_live_videos'] = $this->repository->getrecordedLiveVideos($this->request->type);
            }
        }
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    /**
     * This Function used to get all upcomming and recorded live videos list
     *
     * @return json
     */
    public function browseAllLiveVideos()
    {
        $fetch['server_time'] = date("Y-m-d H:i:s", time());
        $result = $this->repository->fetchLiveVideos();
        if (!$result['error']) {
            $result['data']['server_time'] = date("Y-m-d H:i:s", time());
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result['data']]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }


        /**
     * This Function used to get all upcomming and recorded live videos list
     *
     * @return json
     */
    public function recommendedVideos()
    {
        $videoIds = $this->request->slug_list;
        $result = $this->repository->fetchRecommendedVideos($videoIds);
        if (!$result['error']) {
            //$result['data']['server_time'] = date("Y-m-d H:i:s", time());
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result['data']]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    /**
     * This Function used to get all upcomming and recorded live videos list
     *
     * @return json
     */
    public function browseMoreLiveVideos()
    {
        $fetch['server_time'] = date("Y-m-d H:i:s", time());
        $result = $this->repository->fetchMoreLiveVideos();
        if (!$result['error']) {
            $videoInfo['server_time']           = date("Y-m-d H:i:s", time());
            $videoInfo['upcoming_live_videos']  = $result['data'];
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $videoInfo]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }


    /**
     * This Function used to get live videos list by slug
     *
     * @return json
     */
    public function browseLiveVideoSlug($slug='')
    {
        $country_id = '';$country_name ='';
        $fetchedVideos = $this->repository->getVideoSlug($slug);
        if (isset($fetchedVideos->is_live) && $fetchedVideos->is_live !== 0) {
            $fetch['video_info'] = $fetchedVideos->makeHidden(['video_url', 'youtube_id', 'liveStatus','description','director','imdb_rating','presenter','releaseYear','poster_image','auto_play','season_name','season_id','subtitle','iossubtitles','percentage','current_duration','video_duration','episode_title','series_title','tags','episode_order','created_at']);
            $country_info = DB::select("select c.name as country_name,  cc.country_id,cc.category_id from `country_categories` as cc join `videos` as v ON v.id = cc.video_id join countries as c on cc.country_id = c.id where v.slug = '".$slug."'");
            $country_id = $country_info[0]->country_id;
            $category_id = $country_info[0]->category_id;
            $country_name = $country_info[0]->country_name;
            $fetch['video_info']['hls_playlist_url'] = $this->url_encryptor('encrypt', $fetch['video_info']['hls_playlist_url']);
            $fetch['video_info']['country_id'] = $country_id;
            $fetch['video_info']['country'] = $country_name;
            //$fetch['related'] = $this->repository->getLiverelatedVideos($slug);
            $fetch['similar'] = $this->repository->getLivesimilarVideos($category_id);
        } else {
            $fetch['video_info'] = $fetchedVideos;
            if($fetchedVideos->is_web_series == 1) {
                $fetch['related'] = $this->repository->getSeasonVideoSlug($fetchedVideos, $fetchedVideos->season_id);
            }
            else {
                $fetch['related'] = $this->category->getRelatedVideoSlug($slug, 10, true);
            }
            $fetch['similar'] = [];
        }

        if (array_filter($fetch)) {
            
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch, 'country_id' => $country_id, 'country' => $country_name]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    /**
     * Function to send all video list, category list, tag list
     *
     * @return json
     */
    public function browseVideos()
    { 
        $result = $this->repository->searchAllVideo();
        if (isset($result['error']) && $result['error']) {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        } else {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result['data']]);
        }
    }
        /**
     * Function to send all video cast list, tag list
     *
     * @return json
     */
    public function castList()
    {
        $result = $this->repository->getVideoCast();
        if (isset($result['error']) && $result['error']) {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        } else {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
        }
    }

    /**
     * Function to fetch category based videos
     *
     * @param string $slug
     * @return json
     */
    public function categoriedVideo($slug)
    {
        $fetch['categories'] = $this->category->getAllCategoriesSlugs($slug);
        $fetch['tags'] = $this->repository->getallTags();
        $fetch['videos'] = $this->repository->getallVideo();
        $fetch['live_videos'] = $this->repository->getallTags();
        if ($this->request->header('x-request-type') !== 'mobile') {
            $fetch['customerProfile'] = $this->customerrepositary->getProfile();
        }
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    /**
     * Function to send video details with, related videos, search tag, subscription details, comments
     *
     * @return json
     */
    public function browseVideo($slug = '', $playlist_id = '')
    {
        // if(!$this->domainLicenseValidation()){
        //     return $this->getErrorJsonResponse([], 'unauthorized', 200);
        // }
        if(!empty($this->request->user_id)){
            $user_id = $this->request->user_id;
        }else{
            $user_id = '';
        }

        $fetchedVideos = $this->repository->getVideoSlug($slug);
        if (isset($fetchedVideos->is_live) && $fetchedVideos->is_live !== 0) {
            $fetch['video_info'] = $fetchedVideos->makeHidden(['video_url', 'youtube_id', 'liveStatus']);
            $fetch['related'] = $this->repository->getLiverelatedVideos($slug);
        } else {
            //Log::info('hi');
            $fetch['video_info'] = $fetchedVideos;
            if($playlist_id) {
                //$fetch['related'] = $this->playlist->getPlaylistByVideosRelated($playlist_id, $slug);
            }
            else if($fetchedVideos->is_web_series == 1) {
                //$fetch['related'] = $this->repository->getSeasonVideoSlug($fetchedVideos, $fetchedVideos->season_id);
            }
            else {
                $fetch['related'] = $this->category->getRelatedVideoSlug($slug, 10, true);
            }
        }
       
        //$fetch['comments'] = $this->repository->getCommentsVideoSlug($slug, 3, false);
        //$fetch['seasons'] = ($playlist_id != '' && $playlist_id != 0) ? [] : $this->repository->getSeasons($fetchedVideos);
        //$fetch['payment_info'] = $this->repository->getVideoPaymentInfo($fetch['video_info']->id);

        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }
    /**
     * Function to send video details
     *
     * @return json
     */
    public function browseWatchVideo($slug = '', $user_id = 0, $is_newversion = 0){
        // if(!$this->domainLicenseValidation()){
        //     return $this->getErrorJsonResponse([], 'unauthorized', 403);
        // }
        //echo 'watchVideo start';exit;
        //Log::info('watchVideo start');
        $fetchedVideos = $this->repository->getWatchVideoSlug($slug, $user_id, $is_newversion);
        //Log::info('fetchedVideos '.json_encode($fetchedVideos));
        if($fetchedVideos['status'] == 'authorized'){
            $fetch['videos'] = (isset($fetchedVideos['data']->is_live) && $fetchedVideos['data']->is_live !== 0)
                            ? $fetchedVideos['data']->makeHidden(['video_url', 'youtube_id', 'liveStatus'])
                            : $fetchedVideos['data'];
            //$fetch['payment_info'] = $this->repository->getVideoPaymentInfo($fetch['videos']->id);                            
            return array_filter($fetch) ? $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch])
                                        : $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }else{
            return $this->getErrorJsonResponse(['response' => $fetchedVideos], trans('video::videos.video_unauthorized_access')); 
        }
    }

    /**
    * Get video id based on given slug
     */
    public function getVideoId($slug = '') {
        $video = $this->repository->getVideoId($slug);
        if ($video) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $video]);
        }  
    }
    /**
     * Function to send video details
     *
     * @return json
     */
    public function saveTvodViewCount(){
        $transaction_id = $this->request->transaction_id;
        $complete_percentage = $this->request->complete_percentage;
        $data = $this->repository->getTransactionDetails($this->request->transaction_id);
        if($data == 'success') {
            $result = $this->repository->getVideoPercentageDetail($transaction_id,$complete_percentage);            
            if($result == 'success') {
                $fetchedVideos = $this->repository->insertTvodViewCount($transaction_id);
                $videoResult = $this->getSuccessJsonResponse(['message' => trans('video::videos.view_count_updated'), 'response' => $fetchedVideos]);                
            } else if($result == 'updated'){
                $videoResult = $this->getSuccessJsonResponse([], trans('video::videos.get_view_count'));
            } else {
                $videoResult = $this->getErrorJsonResponse([], trans('video::videos.user_unauthorized_access'));
            }            
        } else {
            $videoResult = $this->getErrorJsonResponse([], trans('video::videos.user_unauthorized_access'));
        }
        return $videoResult;
    }
    /**
     * Function to send video details with related videos
     *
     * @return json
     */
    public function browseVideoRelated($slug)
    {
        $fetch = $this->category->getRelatedVideoSlug($slug);
        $videoResult = $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        if ($fetch) {
            $videoResult = $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            $videoResult = $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
        return $videoResult;
    }
    /**
     * This function used to get the related and trending videos based on type
     *
     * @return json
     */
    public function browseRelatedTrendingVideos()
    {
        $this->category->validateVideoType();

        if ($this->request->type == 'recent' || $this->request->type == 'related') {
            if($this->request->has('playlist_id') && $this->request->playlist_id != '') {
                $fetch = $this->playlist->getPlaylistByVideosRelated($this->request->playlist_id, $this->request->id);
            }
            else if(!empty($this->request->id)){
                $fetch = $this->category->getRelatedVideoSlug($this->request->id);
            }
            else {
                $fetch['recent'] = $this->repository->getVideoByType('recent');
            }
        } else {
            $fetch['trending'] = $this->repository->getVideoByType('trending');
        } 

        if ($fetch) {
            $return = $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            $return = $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
        return $return;
    }

    /**
     * Function to send video details with Comments
     *
     * @return json
     */
    public function browseVideoComments($slug)
    {
        $this->repository->video_id = $this->repository->getVideoSlug($slug, 1);
        $inputArray = $this->request->all();
        if (!empty($inputArray['parent_id']) && !empty($inputArray['comment'])) {
            $this->request->request->add(['video_id' => $this->repository->video_id->id]);
            $replyInfo = $this->commentRepository->addChildComment();
            if ($replyInfo['status']) {
                $success = $replyInfo['message'];
            } else {
                $error = $replyInfo['message'];
            }
        } elseif (!empty($inputArray['comment'])) {
            $this->request->request->add(['video_id' => $this->repository->video_id->id]);
            if ($this->commentRepository->addComment() === 1) {
                $success = trans('video::videos.commentinsert.success');
            } else {
                $error = trans('video::videos.commentinsert.error');
            }
        } else {
            $success = trans('video::videos.fetch.success');
            $error = trans('video::videos.fetch.error');
        }
        $fetch['comments'] = $this->repository->getCommentsVideoSlug($slug);
        if ($fetch['comments'] && !empty($success)) {
            return $this->getSuccessJsonResponse(['message' => $success, 'response' => $fetch['comments']]);
        } else {
            return $this->getErrorJsonResponse([], $error, 422);
        }
    }

    /**
     * This function used to get and post the comments for particular videos
     *
     * @return json
     */
    public function getandpostVideocomments()
    {
        return $this->browseVideoComments($this->request->video_id);
    }

    /**
     * To diplayed the dashboard videos like banner, recent and trending videos
     *
     * @return json
     */
    public function getHome($search ='')
    {
        $fetch['section_one']   = $this->repository->getVideoBlockByType('section_one',$search);
        $fetch['section_two']   = $this->repository->getVideoBlockByType('section_two',$search);
        $fetch['section_three'] = $this->repository->getVideoBlockByType('section_three',$search);
        $result['home_content'] = array_values($fetch);
        
        $result['statistics']  =$this->attachResponse();
        if ($fetch) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }
    /**
     * Function to load next set of videos
     * @return json
     */
    public function getMoreVideos()
    {
        $result = $this->repository->getMore();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        } else {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result['data']]);
        }
    }

    /**
     * Function to clear the view
     * @return json
     */
    public function clearView()
    {
        $result = $this->repository->clearVideoView();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        } else {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.delete_history')]);
        }
    }

    /**
     * Function to fetch reply comments for the video
     * @return json
     */
    public function replyVideocomments()
    {
        $result = $this->commentRepository->replyVideocomments();
        if ($result['error']) {
            return $this->getErrorJsonResponse([], trans('video::videos.comment_error'));
        } else {
            return $this->getSuccessJsonResponse(['response' => $result['data'], 'message' => trans('video::videos.comment_success')]);
        }
    }

    /**
     * Function to fetch homepage banner
     * @return json
     */
    public function fetchHomePageBanner()
    {
        $fetch['banner'] = $this->repository->getVideoBlockByType('banner');
        $fetch['new'] = $this->repository->getVideoBlockByType('new');
        if ($fetch) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    /**
     * Function to send video details with, related videos, search tag, subscription details, comments
     *
     * @return json
     */
    public function browseSeasonVideo($slug, $season = '')
    {
        $fetch['season_list'] = $this->repository->getSeasonVideoSlug($slug, $season);
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    /**
     * Function to send video details with, related videos, search tag, subscription details, comments
     *
     * @return json
     */
    public function browseWebseriesSeasonVideo($slug, $season = '')
    {
        $fetch['season_list'] = $this->repository->getSeasonVideoSlug($slug, $season, 'web-series');
        if (array_filter($fetch)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }
    
    /**
     * Function to validate the key request
     * @return string
     */
    public function getKey(){
        // if(!$this->domainLicenseValidation()){
        //     return $this->getErrorJsonResponse([], 'unauthorized', 403);
        // }
        $key     = request()->input('key');
        \Log::info(" key ".$key);
        $referer = request()->header('Referer');
        $Title   = request()->header('Title');
        $errorMsg = '';

        $TitleWithTime = cryptoJsAesDecrypt($Title);

        $platform   = getPlatform();
        \Log::info(" platform ".$platform);
        $getTime    = explode("/",$TitleWithTime);
        $timeKey    = (isset($getTime[1])) ? $getTime[1] : 0;
        $differenceInSeconds = time() - $timeKey;

        if($referer == env('DOWNLOAD_REFERER') || $platform == 'web'){
            if (strpos($key, 'FFMPEG') !== false) {
                $key = explode('/', $key);
                array_pop($key);
                $key = implode('/', $key) . '/enc.key';
            } else {
                $key = str_replace("m3u8", "key", $key);
            }

            $result = $this->awsRepository->fetchFileFromS3Bucket($key);
            \Log::info("aws bucket result ".json_encode($result));
            $key    = $this->fetchChunkData($platform, $differenceInSeconds, $result);
            \Log::info(" key2 ".$key);
            if($key == '') {
                $errorMsg = 'Unauthorized';
            }
            else {
                return response($key, 200);
            }
        }
        else {
            $errorMsg = 'Crossdomain access denied';
        }
        return $this->getErrorJsonResponse([], $errorMsg);
   }

    public function fetchChunkData($platform, $time, $result) {
        $key        = '';
        $diffSec    = 30;
        $userAgent  = request()->header('User-Agent');

        $castDevice   = request()->header('cast-device-capabilities');
        $cast         = false;
        if(!empty($castDevice)) {
            $cast = true;
        }

        if($result) {
            switch ($platform) {
                case $platform == 'web':
                    if(stripos($userAgent, 'iphone') || stripos($userAgent, 'ipad')  || isAdmin()  || $cast) {
                        $key =  $result['Body'];
                    }
                    else {
                        $key = ($time <= $diffSec) ? cryptoJsAesEncrypt($result['Body']) : '';
                    }
                break;
                case $platform == 'ios':
                    $key = ($time <= $diffSec) ? $result['Body'] : '';
                break;
                case $platform == 'android':
                    $key = ($time <= $diffSec) ? cryptoJsAesEncrypt($result['Body']) : '';
                break;
                default:
                    $key = '';
                break;
            }
        }
        return $key;
   }

    /**
     * get Comment-ID for Deleting Comments
     * 
     * @param int $comment_id
     * @return json
     */
    public function deleteComments($comment_id)
    {  
       
        $delete = $this->commentRepository->deleteComment($comment_id);
      
        return ($delete) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.comment_delete_success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.comment_delete_error' ) );
    }

    /**
     * get BestBox Dashboard  (Popular,new, category and genre wise) -- vinod
     * 
     * @return json
     */
    public function fetchBestBoxDashboard()
    {   
        $type = '';
        if($this->request->has('type') && !empty($this->request->type)){
            $type = $this->request->type;
        }

        if($this->request->has('user_id') && !empty($this->request->user_id)){
            $user_id = $this->request->user_id;
        }else{
            $user_id = 0;
        }

        if(!empty($this->request->is_newversion)){
            $is_newversion = $this->request->is_newversion;
        }else{
            $is_newversion = '';
        }

        if($type == 'vod') {
            $continue_list = $this->repository->fetchVODDashboard(); //for vod
            $result['bannerimages']=$this->getBanner('Movies');
        }else if($type == 'livetv') {
            $livetv['video_list'] = $this->repository->fetchLiveTVDashboard(); //for live tv
            foreach($livetv['video_list']['data'] as $j => $item) {
                if($is_newversion) {
                    $item['hls_playlist_url'] = $this->url_encryptor('encrypt',$item['hls_playlist_url']);
                }
                $country_info = DB::select("select cc.country_id from `country_categories` as cc join  `videos` as v ON v.id = cc.video_id where v.slug = '".$item['slug']."'");
                $item['country_id'] = $country_info[0]->country_id;
                //$item['duration'] = 0; $item['episode_title'] = '';$item['series_title'] = '';
                $livetv['video_list']['data'][$j] = $item;
            }
            
            //$b[$i]['data'] = $videos;
            $livetv['title'] = 'Live Tv';
            $result['main'][] = $livetv;
            $result['bannerimages']=$this->getBanner('Live');
        }else if($type == 'webseries') {
            $continue_list = $this->repository->getContinuewatchList(); 
            $series['video_list'] = $this->category->getAllWebseries();
            foreach($series['video_list']['data'] as $j => $item) {
                $item['country_id'] = 0;
                //$item['duration'] = 0; $item['episode_title'] = '';$item['series_title'] = ''; 
                $series['video_list']['data'][$j] = $item;
            }
            
            $series['title'] = 'Series';
            $series['slug'] = 'web-series';
            $result['main'][] = $series;
            
            $result['bannerimages']=$this->getBanner('Series');
        }else{
            //$cont_list['video_list'] =  $this->repository->getContinuewatchList();
            //$cont_list['video_list'] = $cont_list;
            // $cont_list['title'] = 'Continue Watch List';
            // $cont_list['slug'] = 'continue-watch-list';
            // $cont_list['id'] = '';
            // $cont_list['type'] = '';
            
            $result = $this->repository->fetchVODDashboard(); //for vod
           // $result['main'][] = $cont_list;
            
            $livetv['video_list'] = $this->repository->fetchLiveTVDashboard(); //for live tv
            foreach($livetv['video_list']['data'] as $j => $item) {
                if($is_newversion) {
                    $item['hls_playlist_url'] = $this->url_encryptor('encrypt',$item['hls_playlist_url']);
                }
                $country_info = DB::select("select cc.country_id from `country_categories` as cc join  `videos` as v ON v.id = cc.video_id where v.slug = '".$item['slug']."'");
                $item['country_id'] = $country_info[0]->country_id;
                //$item['duration'] = 0; $item['episode_title'] = '';$item['series_title'] = '';
                $livetv['video_list']['data'][$j] = $item;
            }
            $livetv['title'] = 'Live Tv';
            $livetv['isActive'] = 1;
            $result['main'][] = $livetv;
            $series['video_list'] = $this->category->getAllWebseries();
            foreach($series['video_list']['data'] as $k => $item2) {
                $item2['country_id'] = 0;
                //$item2['duration'] = 0; $item2['episode_title'] = '';$item2['series_title'] = ''; 
                $series['video_list']['data'][$k] = $item2;
            }
            $series['title'] = 'Series';
            $series['slug'] = 'web-series';
            $series['isActive'] = 1;
            $result['main'][] = $series;
            $vodbannerImages= $this->getBanner('Movies');
            $livebannerImages=$this->getBanner('Live');
            $seriesbannerImages=$this->getBanner('Series');
            $topBannerImages = array_merge($vodbannerImages, $seriesbannerImages, $livebannerImages);
            shuffle($topBannerImages);
            $result['bannerimages']= $topBannerImages;
          // $result['bannerimages']= array();
        }
              
        if (array_filter($result)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    
    /**
     * get VOD Dashboard showmore per page (Popular,new, category and genre wise)
     * 
     * @return json
     */
    public function fetchVODShowmore()
    {  
        $result = $this->repository->fetchVODCategroyWise(); 
       
        if (array_filter($result)) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
    }

    public function gettopList()
    {
        $result = $this->repository->gettopList(); 
        if ($result) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
        } else {
            return $this->getErrorJsonResponse([], trans('video::videos.fetch.error'));
        }
        return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
    }

    public function getCountryWisefilterTV(){

        if(!empty($this->request->category)){
            $cat_id = $this->request->category;
        }else{
            $cat_id = '';    
        }
        if(!empty($this->request->country_id)){
            $c_id = $this->request->country_id;
        }else{
            $c_id = '';    
        }
        if(!empty($this->request->web)){
            $web = $this->request->web;
        }else{
            $web = '';    
        }
                
        $result = $this->repository->getCountryWiseTV($c_id, $cat_id, $web); 
        $livebannerImages=$this->getBanner('Live');
        return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result,'livebannerImages'=>$livebannerImages]);

    }

    //get categories by country wise

    public function getCategoriesCountryWise(){

        if(!empty($this->request->country_id)){
            $c_id = $this->request->country_id;
        }else{
            $c_id = '';    
        }
        if(!empty($this->request->type)){
            $type = $this->request->type;
        }else{
            $type = '';
        }
        
        $result = $this->repository->getCategoriesCountryWise($c_id, $type); 
        return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);

    }

    public function getLivetvCountryList(){

        $result = array();//$this->repository->getLivetvCountryList(); 
        return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);

    }

    //get the Continue Watch List -- vinod
    public function getContinuewatchList() {

        $result = $this->repository->getContinuewatchList(); 
        if ($result) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
        } else {
            return $this->getErrorJsonResponse(['message' => trans('video::videos.fetch.error'),'response' => $result]);
        }
    }

    //update video duration  -- vinod

    public function updateVideoDuration() {

        $result = $this->repository->updateVideoDuration(); 
        if ($result) {
            return $this->getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $result]);
        } else {
            return $this->getErrorJsonResponse(['message' => trans('video::videos.fetch.error'),'response' => $result]);
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
