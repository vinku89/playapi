<?php

/**
 * Category Controller
 *
 * To manage the video categories.
 *
 * @name Category Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 *
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Illuminate\Http\Request;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Notification\Models\Notification;
use Contus\Video\Models\ContinueWatchHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CategoryController extends ApiController
{
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     *
     * @param CategoryRepository $categoryRepository
     * @param UploadRepository $uploadRepository
     * @param NotificationRepository $notificationrepositary
     */
    public function __construct(CategoryRepository $categoryRepository, UploadRepository $uploadRepository, NotificationRepository $notificationrepositary, FrontVideoRepository $frontVideoRepository)
    {
        parent::__construct();
        $this->repository = $categoryRepository;
        $this->uploadRepository = $uploadRepository;
        $this->notificationrepository = $notificationrepositary;
        $this->videoRepository = $frontVideoRepository;

        $this->repoArray = ['repository', 'uploadRepository', 'notificationrepository'];
    }

    /**
     * Get Categories for the tabs in navigation
     *
     * @return json
     */
    public function getCategoriesNav()
    {
        $isCategoriesUnique = $this->getCacheData('dashboard_categorynave', $this->repository, 'getCategoiesNav');

        $this->getCAcheExpiresTime('youtube_live');
        $video = Video::where('youtube_live', 1)->where('is_active', 1)->where('is_archived', 0)->where('youtube_live', 1)->where('scheduledStartTime', '!=', '')->whereRaw('scheduledStartTime > "' . Carbon::now()->toDateTimeString() . '"')->select(DB::raw('videos.*, DATE(scheduledStartTime) as dates'))->whereRaw('liveStatus!="complete"')->orderBy('scheduledStartTime', 'asc')->first();

        if (!empty($video) && count($video->toArray()) > 0) {
            $video ['timer'] = ( int ) (strtotime($video->scheduledStartTime) - time());
        }
        return ($isCategoriesUnique) ? $this->getSuccessJsonResponse([ 'response' => $isCategoriesUnique,'live' => $video,'message' => 'Success' ]) : $this->getErrorJsonResponse([ ], 'Failed');
    }
    /**
     * Funtion to clear all cache
     */
    public function clearAllCache()
    {
        $cacheKeys = array('category_listing_page','dashboard_categories','dashboard_exams','dashboard_categorynave','dashboard_live','dashboard_trending','dashboard_banner_image','dashboard_testimonials','dashboard_customer_count','dashboard_video_count','dashboard_pdf_count','dashboard_audio_count' );
        if (count($cacheKeys)) {
            for ($i = 0; $i < count($cacheKeys); $i ++) {
                Cache::forget($cacheKeys [$i]);
            }
        }
        if (Cache::has('cache_keys_playlist')) {
            $cacheKeys = Cache::get('cache_keys_playlist');
            $cacheKeys = explode(",", $cacheKeys);
            foreach ($cacheKeys as $keys) {
                Cache::forget($keys);
            }
            Cache::forget('cache_keys_playlist');
        }
    }
    /**
     * Get categories for the navigation
     *
     * @return json
     */
    public function getCategoriesNavList()
    {
        $isCategoriesUnique = $this->getCacheData('category_listing_page', $this->repository, 'getCategoiesNav', true);
        return ($isCategoriesUnique) ? $this->getSuccessJsonResponse([ 'response' => $isCategoriesUnique,'message' => 'Success' ]) : $this->getErrorJsonResponse([ ], 'Failed');
    }

    /**
     * Get categories for the exams
     *
     * @return json
     */
    public function getCategoriesExams()
    {
        $data = $this->repository->browsepreferenceListAll();
        return ($data) ? $this->getSuccessJsonResponse([ 'message' => trans('video::playlist.successfetchall'),'response' => $data ]) : $this->getErrorJsonResponse([ ], trans('video::playlist.errorfetchall'));
    }

    public function categoryList()
    {
        $is_live = 0;
        if($this->request->has('is_live') && !empty($this->request->is_live)){
            $is_live = $this->request->is_live;
        }
        $categories['category_list'] = $this->repository->getMainCategory($is_live);
        if (isset(authUser()->id)) {
            $categories['notification_count'] = $this->notificationrepository->getNotificationCount(authUser()->id);
        }
        $vodbannerImages=$this->getBanner('Movies');
        $seriesbannerImages=$this->getBanner('Series');
        /*$topBannerImages = array_merge($vodbannerImages, $seriesbannerImages);
        shuffle($topBannerImages);*/
        return ($categories) ? $this->getSuccessJsonResponse([ 'message' => trans('video::categories.fetched'),'response' => $categories ,'vodbannerImages'=>$vodbannerImages,'seriesbannerImages'=>$seriesbannerImages]) : $this->getErrorJsonResponse([ ], trans('general.fetch_failed'));
    }

    /**
     * Get all catgories unser the web series
    */

    public function parentWebseriesList()
    {
        $categories['category_list'] = $this->repository->parentWebseriesList();
        return ($categories) ? $this->getSuccessJsonResponse([ 'message' => trans('video::categories.fetched'),'response' => $categories ]) : $this->getErrorJsonResponse([ ], trans('general.fetch_failed'));
    }

    /**
     * Get all parent web series
    */

    public function getAllWebseries()
    {
        $categories['category_list'] = $this->repository->getAllWebseries();
        return ($categories) ? $this->getSuccessJsonResponse([ 'message' => trans('video::categories.fetched'),'response' => $categories ]) : $this->getErrorJsonResponse([ ], trans('general.fetch_failed'));
    }

     /**
     * Get all single web series deatil and season and episode
    */

    public function browseWebseries($slug = '',$season_id = 0,$user_id = 0)
    {      
        $seasons = [];
        $error = 'Series does not exist';
        $webseries_info = $this->repository->browseWebseries($slug);
        if ($webseries_info) {        
            $category_id = Category::where('slug', $webseries_info->slug)->pluck('id');
            \Log::info($category_id);
            $category['webseries_info'] = $webseries_info;
            $videoCategory = DB::table('video_categories')->whereIn('category_id', $category_id)->get();
            $no_of_episodes = DB::table('video_categories')->where('category_id', $category_id)->count();
            $category['webseries_info']['total_episodes'] = $no_of_episodes;
            
            $seasons = $this->repository->getVideoSeasons($webseries_info);
            if (count($videoCategory) > 0 && (!empty($seasons))) {
                $fetchedVideos = Video::find($videoCategory[0]->video_id);
                if($season_id != 0){
                    $category['related'] = $this->repository->getVideoSeasonVideoSlug($fetchedVideos, $season_id);
                }else{
                    $category['related'] = $this->repository->getVideoSeasonVideoSlug($fetchedVideos, $seasons[0]['id']);
                }
                if(!empty($category['related']) && $user_id !=0){
                    foreach($category['related']['data'] as $i => $item) {
                        $result = $this->getCurrentDuration($item['slug'],$user_id);
                        $item['current_duration'] = $result['current_duration'];
                        $result = $this->calculatePercentage($item['video_duration'], $item['current_duration']);
                        $item['percentage'] = $result['percentage'];
                        $category['related']['data'][$i] = $item;
                    }
                }
                
                $this->video = new Video();
                $this->video = $this->video->whereCustomer()->where('is_active', 1);
             
                //$category['seasons'] = $seasons;
                foreach($seasons as $season) {
                    $season_count = $this->repository->getSeasonCount($fetchedVideos, $season['id']);
                    $category['seasons'][] = array('id' => $season['id'], 'title' => $season['title'], 'season_count' => $season_count);
                }
                
            } else {
                $category['related'] = Video::where('id', null)->paginate(50);
                $category['seasons'] = [];
               // $category['season_count'] = [];
            } 
            $category['webseries_info']['imdb_rating'] = 0;
            $category['webseries_info']['releaseYear'] = '';
            $category['webseries_info']['presenter'] = '';
            $category['webseries_info']['director'] = '';
            if(!empty($category['related']) && count($category['related']['data'])>0){
                foreach($category['related']['data'] as $item){
                    $i=0;
                    if($i>0) continue;
                    $category['webseries_info']['description'] = $item['description'];
                    $category['webseries_info']['imdb_rating'] = $item['imdb_rating'];
                    $category['webseries_info']['releaseYear'] = $item['releaseYear'];
                    $category['webseries_info']['presenter'] = $item['presenter'];
                    $category['webseries_info']['director'] = $item['director'];$i++;
                }
            }
            return $this->getSuccessJsonResponse([ 'message' => trans('video::categories.fetched'),'response' => $category ]);
        }
        return $this->getErrorJsonResponse([], $error, 422);
    }

    public function calculatePercentage($total_duration, $current_duration= 0) {
        $flag = 0; 
        $duration = explode(':',$total_duration);
        if(isset($duration[0])) $tot_duration = ($duration[0]*60*60);
        if(isset($duration[1])) $tot_duration += ($duration[1]*60);
        if(isset($duration[2])) $tot_duration += ($duration[2]);
        $percentage = (int)(($current_duration/$tot_duration)*100);
        $flag = ($percentage < 95) ? 1 : 0;
        return array('percentage' => $percentage, 'flag' => $flag);
      }
      

     /**
     * Get all web series based on the 
    */

    public function browseChildWebseries($slug = '', $perpage=20, $search ='')
    {
        $category['web_series_detail'] = $this->repository->browseChildWebseries($slug,$search,$perpage);
        return ($category) ? $this->getSuccessJsonResponse([ 'message' => trans('video::categories.fetched'),'response' => $category ]) : $this->getErrorJsonResponse([ ], trans('general.fetch_failed'));
    }

    public function browseChildWebseriesMobile($slug = '', $perpage=20, $search ='')
    {
        
        $category['new'] = $this->repository->browseChildWebseriesMobileNew($slug,$search,$perpage);
        //$category['popular'] = $this->repository->browseChildWebseriesMobilePopular($slug,$search,$perpage);
        $category['web_series_detail'] = $this->repository->browseChildWebseries($slug,$search,$perpage);
        $seriesbanner = $this->getBanner('Series'); 
        
        return ($category) ? $this->getSuccessJsonResponse([ 'message' => trans('video::categories.fetched'),'response' => $category,'banners'=>$seriesbanner ]) : $this->getErrorJsonResponse([ ], trans('general.fetch_failed'));
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
}
