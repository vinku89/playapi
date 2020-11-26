<?php

/**
 * VideoTrait
 *
 * To manage the functionalities related to the Videos module from Video Controller
 *
 * @vendor Contus
 *
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Traits;

use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\Group;
use Carbon\Carbon;
use Contus\Video\Models\Like;
use Contus\Video\Models\PlaylistVideos;
use Contus\Video\Models\UserPlaylist;
use Contus\Video\Models\Category;
use Contus\Video\Models\WatchHistory;
use Contus\Video\Models\Season;
use Contus\Video\Models\Video;
use Contus\Video\Models\VideoAnalytic;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\VideoTranslation;
use Contus\Video\Models\FavouriteVideo;
use Location;
use Contus\Video\Models\Ads;
use Contus\Video\Models\VideoAds;
use Contus\Video\Models\Comment;
use Illuminate\Support\Facades\DB;

trait VideoTrait
{
    /**
     * HasMany relationship between videos and video_posters
     */
    public function recentlyViewed()
    {
        return $this->belongsToMany(Customer::class, 'recently_viewed_videos', 'video_id', 'customer_id');
    }

    /**
     * HasMany relationship between videos and video_countries
     */
    public function recentlyWatched()
    {
        return $this->hasMany(WatchHistory::class, 'video_id', 'id');
    }

    public function getIsFavouriteAttribute()
    {
        $favStatus = false;
        if (!empty(authUser()->id)) {
            $favStatus = FavouriteVideo::where('customer_id', authUser()->id)->where('video_id', (int) $this->id)->exists();
        }
        
        return ($favStatus) ? 1 : 0;
    }

    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function favourite()
    {
        return $this->belongsToMany(Customer::class, 'favourite_videos');
    }

    /**
     * belongsToMany relationship between collection and collections_videos
     */
    public function group()
    {
        return $this->belongsToMany(Group::class, 'collections_videos', StringLiterals::VIDEOID, 'group_id')->withTimestamps();
    }

    public function getCollectionAttribute()
    {
        if ($this->group()->count() > 0) {
            return $this->group()->first()->toArray();
        }
        return new \stdClass();
    }

    /**
     * Set explicit model condition for mobile
     *
     * {@inheritdoc}
     *
     * @see \Contus\Base\Model::whereliveVideo()
     *
     * @return object
     */
    public function whereliveVideos()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('is_live', 1)->where('liveStatus', '!=', 'complete');
        }
    }

    /**
     * Check whether user is liked the video or not
     *
     * @return object
     */
    public function getIsLikeAttribute()
    {
        $likeStatus = false;
        if (auth()->user()) {
            $likeStatus = Like::where('user_id', auth()->user()->id)->where('video_id', (int) $this->id)->where('type', Like::TYPE['like'])->exists();
        }
        return ($likeStatus) ? 1 : 0;
    }

    /**
     * Check whether user is disliked the video or not
     *
     * @return object
     */
    public function getIsDislikeAttribute()
    {
        $likeStatus = false;
        if (auth()->user()) {
            $likeStatus = Like::where('user_id', auth()->user()->id)->where('video_id', (int) $this->id)->where('type', Like::TYPE['dislike'])->exists();
        }
        return ($likeStatus) ? 1 : 0;
    }

    /**
     * Get the count of liked videos
     *
     * @return object
     */
    public function getLikeCountAttribute()
    {
        return Like::where('video_id', (int) $this->id)->where('type', Like::TYPE['like'])->count();
    }

    /**
     * Get the count of disliked videos
     *
     * @return object
     */
    public function getDislikeCountAttribute()
    {
        return Like::where('video_id', (int) $this->id)->where('type', Like::TYPE['dislike'])->count();
    }

    /**
     * Get the count of comments videos
     *
     * @return object
     */
    public function getCommentsCountAttribute()
    {
        return Comment::where('video_id', (int) $this->id)->count();
    }

    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function userPlaylist()
    {
        return $this->belongsToMany(UserPlaylist::class, 'playlist_videos', 'video_id', 'playlist_id');
    }

    /**Category
    * Get the category name
    * @return string
    */
    public function categoryName($id)
    {
        $categoryString = '';
        $category = Category::with('parent_category')->find($id);
        if (!empty($category->parent_category)) {
            $categoryString = $category->parent_category->title . ',';
        }
        $categoryString .= $category->title;
        return $categoryString;
    }

    /**
    * Get the genre name
    * @return string
    */
    public function genreName($id)
    {
        return Group::find($id)->name;
    }

    /**
    * Get the genre name
    * @return string
    */
    public function getGenreNameAttribute()
    {
        $genre = $this->group()->first();
        if (!empty($genre)) {
            return $genre->name;
        }
        return '';
    }

    /**
    * Get the category name
    * @return string
    */
    public function getVideoCategoryNameAttribute()
    {
        $categoryString = '';
        $categories = $this->categories()->first();
        if (!empty($categories)) {
            $categoryString = $categories->title;
        }
        return $categoryString;
    }

    /**
    * Get the category name
    * @return string
    */
    public function getCategoryNameAttribute()
    {
        $categoryString = '';
        $categories = $this->categories()->first();
        if (!empty($categories) && $categories->parent_category()) {
            $categoryString = $categories->parent_category->title . ',';
        }
        if (!empty($categories)) {
            $categoryString .= $categories->title;
        }
        return $categoryString;
    }
    /**
    * Get the season name
    * @return string
    */
    public function getSeasonNameAttribute()
    {
        $videoIds = $this->id;
        $seasonName = Season::whereHas('videoSeason', function ($query) use ($videoIds) {
            $query->where('video_id', $videoIds);
        })->select('title')->where('is_active', 1)->first();
        return !empty($seasonName) ? $seasonName->title : '';
    } 
    /**
    * Get the season id
    */
    public function getSeasonIdAttribute()
    {
        $videoIds = $this->id;
        $seasonName = Season::whereHas('videoSeason', function ($query) use ($videoIds) {
            $query->where('video_id', $videoIds);
        })->select('id')->where('is_active', 1)->first();

        return !empty($seasonName) ? $seasonName->id : '';
    } 

    /**
     * Get the tags names
     * @return string
     */
    public function tagNames()
    {
        return implode(',', $this->tags()->get()->pluck('name')->toArray());
    }

    /**
     * Funtion to append the demo feature in video listing page and detail page
     *
     * @return boolean
     */
    public function getIsSubscribedAttribute()
    {
        if(!empty(authUser()->id)) {
            return authUser()->isExpires() ? 1 : 0;
        }
        else {
            return 0;
        }
    }

    /**
     * Funtion to append the demo feature in video listing page and detail page
     *
     * @return boolean
     */
    public function getAutoPlayAttribute()
    {
        if(!empty(authUser()->id) && authUser()->notificationUser()->count()) {
            return authUser()->notificationUser->auto_play;
        }
        else {
            return 1;
        }
    }

    /**
    * Get the is web series
    */
    public function getIsWebSeriesAttribute() {   
        $isWebSeries = 0;
        $videoIds = $this->id;
        $video = Video::where('id', $videoIds)->first();
        if(!empty($video->categories()->first())) {
            $catInfo = $video->categories()->first();
            $parentInfo = $catInfo->parent_category()->first();
            if(!empty($parentInfo) && $parentInfo->is_web_series == 1) {
                $isWebSeries = 1;
            }
        } 
        return $isWebSeries;
    }

    public function getPublishedOnAttribute() {
        return Carbon::parse($this->created_at)->toDateString();
    } 
    /**
     * Method to record video analytics data 
     * @param $video array
     * 
     * @return boolean
     */
    public function addVideoAnalytics($video){
        $ip = '';
        $videoAnalyticsData = array();
        /** This is call to the helper method to the get the IP address */
        $ip = getIPAddress();
         /** This is call to a method to get the current logged in user country based on the IP */
         $getcurrentIPLocation = Location::get($ip);
         $getcurrentIPLocationFlag = (isset($getcurrentIPLocation->countryName))?$getcurrentIPLocation->countryName:'unknown';
         /** Call to method to get the platform (Web, ios or android) of the request */
         $platform = getPlatform();
         $customerId = (!empty(authUser()->id))?authUser()->id:0;
         $videoAnalyticsData = [
             'video_id'=>$video->id,
             'video_title'=>$video->title,
             'customer_id' => $customerId,
             'country' => $getcurrentIPLocationFlag,
             'platform' => $platform,
         ];
         /** Set validator to check if all the parameters exist needed for video analytics */
        $validator = Validator::make($videoAnalyticsData, [
            'video_id' => 'required|integer',
            'video_title' => 'required|string',
            'customer_id' => 'required|integer',
            'country' => 'required|string',
            'platform' => 'required|string',
        ]);
        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            foreach($messages as $message){
                app('log')->error(' ###File : VideoTrait.php ##Message : The video analytics insertion failed  ' .' #Error : ' . $message[0]);
            }
       }else{
        $videoAnalytic = new VideoAnalytic();
            try{
                $videoAnalytic->fill($videoAnalyticsData);
                return ($videoAnalytic->save())?true:false;
            }
            catch(Exception $e) {
                app('log')->error(' ###File : VideoTrait.php ##Message : The video analytics insertion failed  ' .' #Error : ' . $e->getMessage());
            }
        }
        return false;
    }

/**
     * Function to fetch season in video detail Api
     */
    public function getSeasons($video) {
        $videoIds = [];
        $activeVideoIds = [];
        $seasonArray = [];
        if(!empty($video->categories()->first())) {
            $catInfo = $video->categories()->first();
            $parentInfo = $catInfo->parent_category()->first();
            if(!empty($parentInfo) && $parentInfo->is_web_series == 1) {
                $category = $catInfo->id;
                $videoIds = $this->getvideoIdByCategory($category);
                $activeVideoIds = Video::whereIn('id', $videoIds)->where('is_active', 1)->pluck('id');
                $seasonArray =  Season::whereHas('videoSeason', function ($query) use ($activeVideoIds) {
                    $query->whereIn('video_id', $activeVideoIds);
                })->where('is_active', 1)->orderBy('season_order', 'asc')->get()->toArray();
            }
        } 
        return $seasonArray; 
        
    }
    /**
     * Function to fetch video ids
     */
    public function getvideoIdByCategory($category){
        return VideoCategory::where('category_id', $category)->pluck('video_id')->toArray();
    }

    /**
     * Function to fetch season videos
     */
    public function getSeasonVideoSlug($video, $season, $type = null) {

        $category = '';
        if(!is_object($video)) {
            \Log::info('executed');
            $video = Video::where($this->getKeySlugorId(), $video)->first();
        }
        if(!empty($video->categories()->first())) {
            $category = $video->categories()->first()->id;
        }

        $this->video = new Video();
        // if Web series section we'll show all the seasons videos
        if ($type == 'web-series') {
            $this->video = $this->video->whereCustomer()->where('is_active', 1);
        } else {
            $this->video = $this->video->whereCustomer()->where('is_active', 1)
            ->where('slug', '!=' , $video->slug); 
        }

        /*selectRaw('videos.id, videos.episode_order, videos.description, videos.title, videos.slug, videos.poster_image ,videos.thumbnail_image, videos.subtitle, videos.video_duration, videos.releaseYear, videos.presenter, videos.director, videos.imdb_rating, videos.id as is_favourite, videos.id as video_category_name, videos.id as is_like, videos.id as is_dislike, videos.id as like_count, videos.id as dislike_count, videos.id as auto_play, videos.id as season_name, videos.id as season_id, videos.price, videos.id as video_category_slug,videos.id as parent_category_slug')->groupBy('videos.id')->orderBy('videos.episode_order', 'asc');*/
        
        $this->video = $this->video->whereHas('season', function ($query) use ($season) {
            $query->where('season_id', $season);
        })->whereHas('categories', function($query) use ($category) {
            $query->where('categories.id', $category);
        })->selectRaw('videos.id, videos.episode_order, videos.description, videos.title, videos.slug, videos.poster_image ,videos.thumbnail_image, videos.video_duration, videos.releaseYear, videos.presenter, videos.director, videos.imdb_rating, videos.id as video_category_name, videos.id as season_name, videos.id as season_id, videos.id as video_category_slug,videos.id as parent_category_slug')->groupBy('videos.id')->orderBy('videos.episode_order', 'asc');
        
        $result = $this->video->paginate(50)->toArray();

        return $result;
        //return $this->video->count();
    }

    /**
     * Function to fetch season videos
     */
    public function getSeasonCount($video, $season) {

        $category = '';
        if(!is_object($video)) {
            \Log::info('executed');
            $video = Video::where($this->getKeySlugorId(), $video)->first();
        }
        if(!empty($video->categories()->first())) {
            $category = $video->categories()->first()->id;
        }

        $this->video = new Video();
        // if Web series section we'll show all the seasons videos
        $this->video = $this->video->whereCustomer()->where('is_active', 1);
        
        $this->video = $this->video->whereHas('season', function ($query) use ($season) {
            $query->where('season_id', $season);
        })->whereHas('categories', function($query) use ($category) {
            $query->where('categories.id', $category);
        })->selectRaw('videos.id as season_id')->groupBy('videos.id')->orderBy('videos.episode_order', 'asc');
        return count($this->video->get()->toArray());
    }

    public function formatLiveNotification($videos, $type) {
        $string = '';
        $videoCount = $videos->count();
        if($type == 1) {
            if ($videos->toArray()) {
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($videos [$i])) {
                        continue;
                    }
                    $string .= '<tr><td><a target="_blank" href="' . env('LS_TYPE_FRONT') . '/video-detail/' . $videos [$i]->slug . '">' . $videos [$i]->title . '</a></td></tr>';
                }
                $string = '<p>Check out the latest ' . $videoCount . ' videos added at ' . config()->get('settings.general-settings.site-settings.site_name') . '</p>
                <table>' . $string . '</table><p><a target="_blank" href="' . env('LS_TYPE_FRONT') . '">View more videos from our site</a><p>';
                Video::where('is_archived', 0)->where('is_active', 1)->where('job_status', 'Complete')->where('notification_status', 0)->where('is_live', 0)->update(['notification_status' => 1]);
            }
        }
        else {
            if ($videos->toArray()) {
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($videos [$i])) {
                        continue;
                    }
                    $string .= '<tr><td><a target="_blank" href="' . env('LS_TYPE_FRONT') . '/video-detail/' . $videos [$i]->slug . '">' . $videos [$i]->title . '</a></td></tr>';
                }
                $string = '<p>' . config()->get('settings.general-settings.site-settings.site_name') . ' has scheduled ' . $videoCount . ' videos for tomorrow.</p>
                <table>' . $string . '</table><p><a target="_blank" href="' . env('LS_TYPE_FRONT') . '">View all live videos from our site</a><p>';
                $string = '<h2>Live videos scheduled for tomorrow.&nbsp;</h2><p>' . $string . '</p>';
                Video::where('is_archived', 0)->where('is_active', 1)->where('liveStatus', 'ready')->where('job_status', 'Complete')->whereRaw('DATE(scheduledStartTime) = "' . Carbon::now()->tomorrow()->toDateString() . '"')->where('notification_status', 0)->where('is_live', 1)->orderBy('scheduledStartTime', 'asc')->update(['notification_status' => 1]);
            }
        }
        return $string;
    }


    /**
     * belogsToMany relationship between video and video_translation
     */
    public function videoTranslation() {
        return $this->hasMany(VideoTranslation::class, 'video_id');
    }

    public function getTitleAttribute($value) {
        $trans = $this->videoTranslation()->where('language_id', $this->fetchLanugageId())->first();
        if(!empty($trans)) {
            return $trans->title;
        }
        return $value;
    }

    public function getDescriptionAttribute($value) {
        $trans = $this->videoTranslation()->where('language_id', $this->fetchLanugageId())->first();
        if(!empty($trans)) {
            return $trans->description;
        }
        return $value;
    }
    
    public function getPresenterAttribute($value) {
        $trans = $this->videoTranslation()->where('language_id', $this->fetchLanugageId())->first();
        if(!empty($trans)) {
            return $trans->presenter;
        }
        return $value;
    }


    public function fetchTranslationInfo($vId) {
        return app('cache')->tags([getCacheTag(), 'video_translation'])->remember(getCacheKey(1).'_global_video_translation_'.$vId, getCacheTime(), function (){
            return $this->videoTranslation()->where('language_id', $this->fetchLanugageId())->first();
        });
    }

    public function fetchVideoUrl($videoId) {
        $videoInfo = Video::where('id', $videoId)->pluck('video_url');

        $result['org'] = $videoInfo[0];
        $result['path'] = (!empty($videoInfo)) ? cryptoJsAesEncrypt($videoInfo[0]) : '';
        return $result;
    }

    public function getSubtitleAttribute($value) {
        $result['base_url'] = env('AWS_BUCKET_URL');
        $result['subtitle_list'] = [];
        if($value != '') {
            $result['subtitle_list'] = json_decode($value);
        }
        return $result;
    }
    public function getIossubtitlesAttribute($value) {
        $result['base_url'] = env('AWS_BUCKET_URL');
        $result['ios_subtitle_list'] = [];
        if($value != '') {
            $result['ios_subtitle_list'] = json_decode($value);
        }
        return $result;
    }

    public function getPassphraseAttribute() {
        $referer        = app()->request->header('Referer');
        $TitleWithTime  = $time = '';

        if($referer == env('DOWNLOAD_REFERER') || isWebsite()){
            $time = time();
            $TitleWithTime = cryptoJsAesEncrypt($time);
        }
        return $TitleWithTime;
    }

    public function getSpriteImageAttribute($value) {
        $imagePath = '';
        if($value != '') {
            $imagePath = env('AWS_BUCKET_URL').$value;
        }
        return $imagePath;
    }

    public function favouriteVideo()
    {
        return $this->hasMany(FavouriteVideo::class,'video_id','id');
    }

    public function videoAds() {
        return $this->hasOne(VideoAds::class, 'video_id', 'id');
    }

    public function getAdsUrlAttribute() {
        $ads_url = '';
        $info = $this->videoAds()->first();
        if(!empty($info)) {
            $ads        = $info->ads()->first();
            $ads_url    = !empty($ads) ? $ads->ads_url : '';
        }
        return $ads_url;
    }
    /**
     * Method to convert geofencing regions into array
     * 
     * @param array/object $geoData
     * @return array
     */
    public function convertRegionsintoArray($geoData){
        $globallyAllowedRegions = array();
        array_walk_recursive($geoData, function($v, $k) use(&$globallyAllowedRegions){
            array_push($globallyAllowedRegions, $v);
        });
        return $globallyAllowedRegions;
    }
    /**
     * Method to return global video view count from the settings
     * 
     * @return string
     */
    public function getGlobalVideoViewCountAttribute(){
        return (int)config ()->get ( 'settings.general-settings.site-settings.video_view_count' );
    }
    /**
     * Method to return the customer video view count
     * 
     * @return int
     */
    public function getCustomerVideoViewCountAttribute(){
       if(!empty(authUser()->id)){
           return WatchHistory::where('video_id','=',$this->id)->where('customer_id','=',authUser()->id)->count();
       }
    }
         /**
    * Get the category slug
    * @return string
    */
    public function getVideoCategorySlugAttribute()
    {
        $categoryString = '';
        $categories = $this->categories()->first();
        if (!empty($categories) && $categories->webseriesDetail()) {
            $categoryString = $categories->webseriesDetail['slug'];
        }
        return $categoryString;
    }

    /**
    * Get the category name
    * @return string
    */
    public function getParentCategorySlugAttribute()
    {
        $categoryString = '';
        $categories = $this->categories()->first();
        if (!empty($categories) && $categories->parent_category()) {
            $categoryString = $categories->parent_category['slug'];
        }
        return $categoryString;
    }
}
