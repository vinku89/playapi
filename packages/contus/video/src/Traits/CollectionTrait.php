<?php

/**
 * CategoryTrait
 *
 * To manage the functionalities related to the Categories module from Categories Controller
 *
 * @vendor Contus
 *
 * @package Categories
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2018 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Traits;

use Contus\Video\Models\Video;
use Contus\Video\Models\CollectionVideo;
use Contus\Video\Models\Group;
use Contus\Video\Models\Category;
use Contus\Video\Models\CountryCategory;
use Contus\Video\Models\Countries;
use Contus\Video\Models\ContinueWatchHistory;
use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Base\Controller;
use Carbon\Carbon;
use Contus\Cms\Models\Banner;
use Contus\Video\Models\WatchHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait CollectionTrait
{

  /**
   * Repository function to delete custom thumbnail of a video.
   *
   * @param integer $id
   * The id of the video.
   * @return boolean True if the thumbnail is deleted and false if not.
   */
  public function deleteThumbnail($id)
  {
    $video = new video();
    /**
     * Check if video id exists.
     */
    if (!empty($id)) {
      $video = $video->findorfail($id);
      /**
       * Delete the thumbnail image using the thumbnail path field from the database.
       */
      $video->thumbnail_image = '';
      $video->thumbnail_path = '';
      $video->save();
      return true;
    } else {
      return false;
    }
  }

  /**
   * Repository function to delete subtitle of a video.
   *
   * @param integer $id
   * The id of the video.
   * @return boolean True if the subtitle is deleted and false if not.
   */
  public function deleteSubtitle($id)
  {
    $video = new video();

    /**
     * Check if video id exists.
     */
    if (!empty($id)) {
      $video = $video->findorfail($id);
      /**
       * Delete the subtitle image using the subtitle path field from the database.
       */
      $video->mp3 = '';
      $video->subtitle_path = '';
      $video->save();
      return true;
    } else {
      return false;
    }
  }
  /**
   * Function to fetch all videos
   *
   * @return json
   */
  public function liveVideoNotification()
  {
    $fetch['live'] = FrontVideoRepository::getLiveVideoNotification();
    return Controller::getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
  }
  /**
   * Funtion to send the related search key for search funtionlaity
   *
   * @return json
   */
  public function searchRelatedVideos()
  {
    $fetch['videos'] = FrontVideoRepository::getallVideo(false);
    if (array_filter($fetch)) {
      return Controller::getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
    } else {
      return Controller::getErrorJsonResponse([], trans('video::videos.fetch.error'));
    }
  }
  /**
   * Function to add the video play tracking list
   *
   * @param id|string $slug
   */
  public function videoPlayTracker($slug)
  {
    (FrontVideoRepository::videoPlayTracker($slug)) ? Controller::getSuccessJsonResponse(['message' => trans('video::videos.fetch.success')]) : Controller::getErrorJsonResponse([], trans('video::videos.fetch.error'));
  }


  /**
   * This function used to get the all the scheduled and recorded videos
   */
  public function AllLiveVideos()
  {
    $fetch['all_live_videos'] = FrontVideoRepository::getAllLiveVideos();
    if (array_filter($fetch)) {
      return Controller::getSuccessJsonResponse(['message' => trans('video::videos.fetch.success'), 'response' => $fetch]);
    } else {
      return Controller::getErrorJsonResponse([], trans('video::videos.fetch.error'));
    }
  }

  /**
   * Funtion to search Videos with respect to video title and description
   *
   * @return json
   */
  public function getSearachVideo()
  {

    $this->setRules(['search' => 'required', 'order' => 'sometimes|in:title', 'sort' => 'sometimes|in:asc,desc']);
    $this->validate($this->request, $this->getRules());

    $searchKey = $this->request->search;
    $video = $this->video->whereCustomer()->where(function ($query) use ($searchKey) {
      $query->orwhere('slug', 'like', '%' . $searchKey . '%')
        ->orwhere('title', 'like', '%' . $searchKey . '%');
    });

    $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.thumbnail_image, videos.hls_playlist_url, videos.id as is_favourite, videos.id as collection';



    $video->with(['categories'])->leftJoin('recently_viewed_videos as f1', function ($j) {
      $j->on('videos.id', '=', 'f1.video_id');
    })->selectRaw($fields)->where('is_live', '==', 0)->groupBy('videos.id');

    $inputArray = $this->request->all();
    if (isset($inputArray['order']) && !empty($inputArray['order'])) {
      $video->orderBy($inputArray['order'], $inputArray['sort']);
    } else {
      $video->orderBy('id', 'desc');
    }

    return $video->paginate(config('access.perpage'));
  }

  /**
   * Function to get the top nth Categories
   * @param  integer $limit - Get the offset of the category to be fetched
   * @return [object]  categoryObject
   */
  public function getTopNthCategory($limit = 0)
  {
    return app('cache')->tags([getCacheTag(), 'categories'])->remember(getCacheKey() . '_top_nth_category_' . $limit, getCacheTime(), function () use ($limit) {
      $catObj = new Category();
      return $catObj->where('parent_id', 0)->where('is_web_series', 0)->where('level', 0)->where('is_active', 1)->orderBy('category_order', 'asc')->skip($limit)->take(1)->first();
    });
  }

  public function fetchRecentVideos($fields, $video)
  {
    return app('cache')->tags([getCacheTag(), 'videos', 'categories', 'groups', 'collections_videos', 'video_categories', 'watch_history'])->remember(getCacheKey() . '_recent_videos', getCacheTime(), function () use ($fields, $video) {
      $userId     = (!empty(authUser()->id)) ? authUser()->id : 0;
      $videoInfo  = $video->whereHas('recentlyWatched', function ($query) use ($userId) {
        $query->where('customer_id', $userId)->where('is_active', 1);
      })->with(['categories'])->selectRaw($fields)->where('is_live', '==', 0)->groupBy('videos.id')->orderBy('updated_at', 'desc')->paginate(config('access.perpage'));
      return $videoInfo->toArray();
    });
  }

  /**
   * Function to get all video to frontend with filters and search
   *
   * @vendor Contus
   *
   * @package video
   * @return array
   */
  public function searchAllVideo()
  {
    $result['error'] = false;
    $result['message'] = '';
    $inputArray = $this->request->all();

    $this->setRules(['order' => 'sometimes|in:title', 'sort' => 'sometimes|in:asc,desc']);
    $this->validate($this->request, $this->getRules());

    $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.thumbnail_image, videos.hls_playlist_url, videos.video_duration, videos.id as is_favourite, videos.id as collection, videos.is_live, videos.presenter, videos.director, videos.imdb_rating ,videos.releaseYear,videos.poster_image';

    $this->video = $this->video->whereCustomer()->where('is_live', '!=', 1)->has('categories')->with('categories');

    $this->video = $this->constructSearchQuery($this->video);

    $video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
      $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw(!empty(authUser()->id) ? authUser()->id : 0));
    })->selectRaw($fields)->groupBy('videos.id');

    if ($this->request->has('video_id')) {
      $video = $video->where('videos.id', '!=', $this->request->video_id);
    }

    $video = $video->paginate(9)->toArray();

    $paramArray = array_filter($inputArray);
    if ((!isset($inputArray['page']) || $inputArray['page'] <= 1) && (!isset($paramArray['category'])) && (!isset($paramArray['genre']))) {
      $genreInfo    = $this->fetchPopularGenre(false);
      unset($genreInfo['category_name']);
      $final['genres'] = $genreInfo;
      $final['categories'] = $this->getChildrenCategory();
    }

    $final['videos'] = $video;
    $result['data'] = $final;
    return $result;
  }

  /**
   * Function to fetch child categories for the given main category
   * @return Object- Return child category object
   */
  public function getChildrenCategory()
  {
    $category = Category::With(['child_category' => function ($query) {
      $query->selectRaw('*, id as video_count');
    }])->where($this->getKeySlugorId(), $this->request->main_category);
    return $category->first();
  }

  /**
   * Function to construct search query based on the requested params
   * @param  Object $videoObj Video Object
   * @return Object Video Object
   */
  public function constructSearchQuery($videoObj)
  {
    $inputArray = $this->request->all();

    if (!empty($inputArray)) {
      foreach ($inputArray as $inputKey => $inputValue) {
        if ($inputValue != '') {
          switch ($inputKey) {
            case 'search':
              $videoObj = $videoObj->where('title', 'like', '%' . $this->request->search . '%');
              break;
            case 'main_category':
              $videoObj = $videoObj->whereHas('categories.parent_category', function ($q) {
                $q->where($this->getKeySlugorId(), $this->request->main_category);
              });
              break;
            case 'category':
              $categoryArray = explode(',', $this->request->category);
              $videoObj = $videoObj->whereHas('categories', function ($q) use ($categoryArray) {
                $q->whereIn('categories.' . $this->getKeySlugorId(), $categoryArray);
              });
              break;
            case 'genre':
              $genreArray = explode(',', $this->request->genre);
              $videoObj = $videoObj->whereHas('collections', function ($q) use ($genreArray) {
                $q->whereIn('groups.' . $this->getKeySlugorId(), $genreArray);
              });
              break;
            default:
              break;
          }
        }
      }

      if (isset($inputArray['order']) && !empty($inputArray['order'])) {
        $videoObj = $videoObj->orderBy($inputArray['order'], $inputArray['sort']);
      } else {
        $videoObj = $videoObj->orderBy('video_order', 'desc');
      }
    }
    return $videoObj;
  }

  /**
   * Function to clear the video view history
   * @return Array
   */
  public function clearVideoView()
  {
    $result['error'] = false;
    $result['message'] = '';
    $videoIds = [];
    $videoIds = $this->fetchVideoIds();
    try {
      if (!empty($videoIds)) {
        WatchHistory::whereIn('video_id', $videoIds)->where('customer_id', (!empty(authUser()->id)) ? authUser()->id : 0)->update(['is_active' => 0]);
      } else {
        WatchHistory::where('customer_id', (!empty(authUser()->id)) ? authUser()->id : 0)->update(['is_active' => 0]);
      }

      app('cache')->tags('videos')->flush();
    } catch (\Exception $e) {
      $result['error'] = true;
      $result['message'] = trans('video::videos.fetch.error');
    }
  }

  /**
   * Function to fetch video ids for the given slug
   * @param  string $slug - video slug
   * @return Array - Video id Array
   */
  public function fetchVideoIds()
  {
    $videoIds = [];
    if ($this->request->has('video_id') && !empty($this->request->video_id)) {
      $videoIds = explode(',', $this->request->video_id);
    }
    if (!isMobile()) {
      $videoIds = Video::whereIn('slug', $videoIds)->pluck('id')->toArray();
    } else {
      if ($this->request->has('video_id') && !empty($this->request->video_id)) {
        $videoIds = array_map('intval', $videoIds);
      }
    }
    return $videoIds;
  }

  public function fetchLiveVideos()
  {
    return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_live_videos', getCacheTime(), function () {
      try {
        $result['error']    = false;
        $result['message']  = '';
        $result['data']  = '';

        $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.poster_image,videos.is_live, videos.hls_playlist_url, videos.is_adult,videos.xmltv_id,videos.custom_siteid';

        if (($this->request->has('country_id') && !empty($this->request->country_id)) && ($this->request->has('category') && !empty($this->request->category))) {
          $videos = $this->video->whereliveVideos()->whereRaw('scheduledStartTime < "' . Carbon::now()->now() . '" ')->orderBy('videos.title', 'ASC')->whereHas('countries', function ($query) {
            $query->where('countries.id', $this->request->country_id);
          })->whereHas('categories', function ($query) {
            $query->where('categories.' . $this->getKeySlugorId(), $this->request->category);
          })->with(['categories.parent_category', 'countries'])->selectRaw($fields);
        } else if (($this->request->has('country_id') && !empty($this->request->country_id))) {
          $videos = $this->video->whereliveVideos()->whereRaw('scheduledStartTime < "' . Carbon::now()->now() . '" ')->orderBy('videos.title', 'ASC')->whereHas('countries', function ($query) {
            $query->where('countries.id', $this->request->country_id);
          })->with(['categories.parent_category', 'countries'])->selectRaw($fields);
        } else {
          $videos = $this->video->whereliveVideos()->whereRaw('scheduledStartTime < "' . Carbon::now()->now() . '" ')->orderBy('videos.title', 'ASC')->whereHas('countries', function ($query) {
            $query->where('countries.code', '!=', 'FA');
          })->with(['categories.parent_category', 'countries'])->selectRaw($fields);
        }

        $videoObj = new Video();
        $todayLive = $videoObj->whereliveVideos()->whereRaw('scheduledStartTime > "' . Carbon::now()->now() . '" ')->whereRaw('scheduledStartTime < "' . Carbon::now()->toDateString() . ' 23:59:59 "')->orderBy('scheduledStartTime', 'asc')->with(['categories.parent_category', 'countries'])->selectRaw($fields)->get();
        $upcomingLive = $this->fetchMoreLiveVideos();
        $videoInfo['banner'] = $this->fetchBannerVideos($fields, $this->video->whereliveVideos(), 1);

        if ($this->request->has('perpage') && !empty($this->request->perpage)) {
            $perPage = $this->request->perpage;
        }else {
            $perPage = 30;
        }

       

        if (($this->request->has('search') && !empty($this->request->search))) {
          $videos = $this->constructSearchQuery($videos);
        }

        if (($this->request->has('is_newversion') && !empty($this->request->is_newversion))) {
          $is_newversion = $this->request->is_newversion;
        }else {
          $is_newversion = '';
        }

        $videoInfo['current_live_videos'] = $videos->paginate($perPage)->toArray();
        foreach($videoInfo['current_live_videos']['data'] as $i => $item){
          // $video_id = $item['id'];
          // $country_info = DB::select("select country_id from `country_categories` where video_id =$video_id");
          // $item['country_id'] = $country_info[0]->country_id;
          
          if($is_newversion) {
            $item['hls_playlist_url'] = $this->url_encryptor('encrypt', $item['hls_playlist_url']);
          }
          $videoInfo['current_live_videos']['data'][$i] = $item;
        }

        $videoInfo['today_live_videos'] = $todayLive->toArray();
        $videoInfo['upcoming_live_videos'] = (!empty($upcomingLive['data'])) ? $upcomingLive['data']->toArray() : [];
        $result['data']  = $videoInfo;
      } catch (\Exception $e) {
        $result['error'] = true;
        $result['message']    = $e->getMessage();
        $result['data']  = '';
      }
      return $result;
    });
  }

  public function fetchMoreLiveVideos()
  {
    return app('cache')->tags([getCacheTag(), 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_more_live_videos', getCacheTime(), function () {
      $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.poster_image,videos.hls_playlist_url, videos.is_live, videos.is_live, videos.xmltv_id,videos.custom_siteid';

      try {
        $result['error']    = false;
        $result['message']  = '';
        $result['data']     = $this->video->whereliveVideos()->whereRaw('scheduledStartTime > "' . Carbon::tomorrow() . '" ')->with(['categories.parent_category', 'countries'])->selectRaw($fields)->orderBy('scheduledStartTime', 'asc')->paginate(config('access.perpage'));
      } catch (\Exception $e) {
        $result['error'] = true;
        $result['message']    = $e->getMessage();
      }
      return $result;
    });
  }

  public function fetchBannerVideos($fields, $video, $live = 0)
  {
    return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_banner_videos_' . $live, getCacheTime(), function () use ($fields, $video) {
      $bannerArray = [];

      $bannerInfo = Banner::selectRaw('id, id as banner_url, banner_image, video_id')->where('is_active', 1)->get();
      if (!empty($bannerInfo)) {
        foreach ($bannerInfo as $bKey => $bImages) {
          $bannerArray[$bImages->video_id] = $bImages->toArray();
        }
      }

      $videoKeys   = array_keys($bannerArray);
      $video = $video->with(['categories'])->selectRaw($fields)->whereIn('videos.id', $videoKeys)->groupBy('videos.id')->orderBy('video_order', 'asc')->orderBy('id', 'desc')->paginate(5);

      if (!empty($video)) {
        $videoCollection = $video->getCollection();
        $video->makeVisible('id');
        $video->setCollection($videoCollection);
        $video = $video->toArray();
        foreach ($video['data'] as $key => $value) {
          $video['data'][$key]['poster_image'] = $bannerArray[$value['id']]['banner_url'];
        }
      } else {
        $video = $video->toArray();
      }

      return $video;
    });
  }

  public function fetchCategoryVideos()
  {
    $this->setRules(['category' => 'required', 'section' => 'sometimes|']);
    $this->validate($this->request, $this->getRules());

    $inputArray = $this->request->all();

    $section = (!empty($inputArray['section'])) ? $inputArray['section'] : 1;
    $catName = !empty($inputArray['category']) ? $inputArray['category'] : '';
    $series = !empty($inputArray['is_web_series']) ? $inputArray['is_web_series'] : 0;
    $search = !empty($inputArray['search']) ? str_replace("%20", ' ', $inputArray['search']) : '';
    $platform = !empty($inputArray['platform']) ? $inputArray['platform'] : '';
    $is_mobile = !empty($inputArray['is_mobile']) ? $inputArray['is_mobile'] : '';
    $user_id = !empty($inputArray['user_id']) ? $inputArray['user_id'] : 0;

    return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories', 'watch_history'])->remember(getCacheKey(1) . '_category_info_cat' . $catName . '_series' . $series . '_section' . $section, getCacheTime(), function () use ($section, $search,$platform,$is_mobile,$user_id) {

      $categoryArray = [];
      $videoInfo['main'] = [];
      $videoInfo['category_videos'] = [];
      $videoInfo['genre_videos'] = [];

      $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.poster_image, videos.created_at';

      $categoryInfo   = $this->fetchChildrens(false);
      $categoryArray  = $this->fetchChildrens(true, $categoryInfo);

      if($platform=='web'){
        if ($section == 1) {
          $video  = $this->video->whereCustomer();

          $new    = $this->fetchNewVideos($fields, $video, $categoryArray, $search);
          if(!empty($new)){
            foreach($new['data'] as $k => $item){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
              // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item['episode_title'] = '';$item['series_title'] = '';
              $item['country_id'] = 0;
              $new['data'][$k] = $item;
            }
          }
          $new    = $this->formatCatVideos('new', $new, $categoryInfo);

          $video    = $this->video->whereCustomer();
          $popular  = $this->fetchPopularVideos($video, $categoryArray, $fields, $search);
          if(!empty($popular)){
            foreach($popular['data'] as $j => $item2){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item2['slug']."' and cw.user_id ='".$user_id."'");
              // $item2['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item2['episode_title'] = '';$item2['series_title'] = '';
              $item2['country_id'] = 0;
              $popular['data'][$j] = $item2;
            }
          }
          $popular  = $this->formatCatVideos('popular', $popular, $categoryInfo);

          $videoInfo['main'][] = $new;
          $videoInfo['main'][] = $popular;
        } else {
          $genreArray     = $this->fetchGenre($categoryInfo);
          
          $genre_result = $this->fetchGenreVideos($genreArray, $search);
                    
          if(isMobile() && !empty($search)) {
            $genre_result = [$genre_result];
          }
          $videoInfo['genre_videos'] = $genre_result;
          if (!$categoryInfo->is_web_series) {
            $category_result = $this->fetchSubCategoryVideos($categoryArray, $search);
            if(isMobile() && !empty($search)) {
              $category_result = [$category_result];
            }

            $videoInfo['category_videos'] = $category_result;
          }
        }
      }else{
        //if ($section == 1) {
          $video  = $this->video->whereCustomer();
          $new    = $this->fetchNewVideos($fields, $video, $categoryArray, $search);
          if(!empty($new)){
            foreach($new['data'] as $k => $item){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
              // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item['episode_title'] = '';$item['series_title'] = '';
              $item['country_id'] = 0;
              $new['data'][$k] = $item;
            }
          }
          $new    = $this->formatCatVideos('new', $new, $categoryInfo);

          $video    = $this->video->whereCustomer();
          $popular  = $this->fetchPopularVideos($video, $categoryArray, $fields, $search);
          if(!empty($popular)){
            foreach($popular['data'] as $j => $item2){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item2['slug']."' and cw.user_id ='".$user_id."'");
              // $item2['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item2['episode_title'] = '';$item2['series_title'] = '';
              $item2['country_id'] = 0;
              $popular['data'][$j] = $item2;
            }
          }
          $popular  = $this->formatCatVideos('popular', $popular, $categoryInfo);

          //all videos
          $video  = $this->video->whereCustomer();
          $all    = $this->fetchAllVideos($fields, $video, $categoryArray, $search);
          if(!empty($all)){
            foreach($all['data'] as $j => $item2){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item2['slug']."' and cw.user_id ='".$user_id."'");
              // $item2['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item2['episode_title'] = '';$item2['series_title'] = '';
              $item2['country_id'] = 0;
              $all['data'][$j] = $item2;
            }
          }
          $all    = $this->formatCatVideos('all', $all, $categoryInfo);

          $videoInfo['main'][] = $new;
          $videoInfo['main'][] = $popular;
          

        //} else {
          $genreArray     = $this->fetchGenre($categoryInfo);
          
          $genre_result = $this->fetchGenreVideos($genreArray, $search);
               
          if(isMobile() && !empty($search)) {
            $genre_result = [$genre_result];
          }
          $videoInfo['genre_videos'] = $genre_result;
          if (!$categoryInfo->is_web_series) {
            $category_result = $this->fetchSubCategoryVideos($categoryArray, $search);
            if(isMobile() && !empty($search)) {
              $category_result = [$category_result];
            }

            $videoInfo['category_videos'] = $category_result;
          }
        //}
        $videoInfo['all'] = [$all];
      }
      

      $videoInfo['web_series'] = (!$categoryInfo->is_web_series) ? 0 : 1;
      return $videoInfo;
    });
  }


  public function fetchGenre($category, $returnVideo = 0)
  {

    $infoArray =  app('cache')->tags([getCacheTag(), 'groups', 'collections_videos'])->remember(getCacheKey() . '_fetch_genre_videos_' . $returnVideo . '_' . $category['id'], getCacheTime(), function () use ($category, $returnVideo) {
      $inputArray = $this->request->all();

      $result['video_info'] = CollectionVideo::where('parent_cateogry_id', $category['id'])->pluck('video_id')->toArray();
      $result['group_info'] = CollectionVideo::where('parent_cateogry_id', $category['id'])->pluck('group_id')->toArray();
      return $result;
    });

    $this->request->request->add(['fetched_category_ids' => $infoArray['video_info']]);

    return $infoArray['group_info'];
  }

  public function getTrendingVideos($categoryArray)
  {
    $catKey = (!empty($categoryArray)) ? implode('#', $categoryArray) : '0';
    return app('cache')->tags([getCacheTag(), 'recently_viewed_videos', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_trending_videos_' . $catKey, getCacheTime(), function () use ($categoryArray) {
      $inputArray = $this->request->all();
      if (isset($inputArray['order']) && !empty($inputArray['order'])) {
        $sort  = (!empty($inputArray['sort'])) ? $inputArray['sort'] : 'asc';
        $order = $inputArray['order'];
      }

      $fields = 'videos.id, videos.is_webseries,  videos.title, videos.slug, videos.description, videos.thumbnail_image,  videos.hls_playlist_url, videos.id as is_favourite, videos.id as collection, videos.poster_image,videos.is_live,videos.view_count,videos.is_premium';

      $video  = $this->video->whereCustomer();

      $perPage = config('access.perpage');
      $order = (!empty($order)) ? $order : 'count';
      $sort = (!empty($sort)) ? $sort : 'desc';
      $video = $video->with(['categories'])->join('recently_viewed_videos', 'videos.id', '=', 'recently_viewed_videos.video_id')->where('recently_viewed_videos.created_at', '>', Carbon::now()->subDays(30))->selectRaw($fields)->where('is_live', '==', 0);

      if (!empty($categoryArray)) {
        $video->whereHas('categories', function ($query) use ($categoryArray) {
          $query->whereIn('categories.id', $categoryArray);
        });
      }

      $video = $video->groupBy('recently_viewed_videos.video_id')->orderBy($order, $sort)->paginate($perPage);
      return $video->toArray();
    });
  }

  public function fetchChildrens($children = true, $categoryInfo = [])
  {
    $inputArray = $this->request->all();
    $catInfo = !empty($categoryInfo->id) ? $categoryInfo->id : 0;
    $catName = !empty($inputArray['category']) ? $inputArray['category'] : '';
    return app('cache')->tags([getCacheTag(), 'categories'])->remember(getCacheKey() . '_fetch_children_' . $children . '_' . $catName . '_' . $catInfo, getCacheTime(), function () use ($children, $categoryInfo, $inputArray) {
      $categoryArray = [];

      if (!empty($categoryInfo) && $children) {
        return $categoryInfo->child_category->pluck('id');
      }

      if (!empty($inputArray['category'])) {
        $categoryInfo = Category::where($this->getKeySlugorId(), $inputArray['category'])->first()->makeVisible(['id']);
        if (!empty($categoryInfo) && $children) {
          $categoryArray = $categoryInfo->child_category->pluck('id');
        }
      }
      return ($children) ? $categoryArray : $categoryInfo;
    });
  }

  public function fetchSubCategoryVideos($categoryArray, $search = '')
  {
    $tempArray = (!is_array($categoryArray)) ? $categoryArray->toArray() : $categoryArray;
    $catInfo = !empty($tempArray) ? implode('#', $tempArray) : '0';
    
    return app('cache')->tags([getCacheTag(), 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_subcategory_videos_' . $catInfo, getCacheTime(), function () use ($categoryArray, $search) {
      $inputArray = $this->request->all();
      if(isset($inputArray['perpage']) && !empty($inputArray['perpage'])) {
        config()->set('access.perpage', $inputArray['perpage']);
      }
      if(isset($inputArray['user_id']) && !empty($inputArray['user_id'])) {
        $user_id = $inputArray['user_id'];
      }else{
        $user_id = 0;
      }
      if (!empty($search)) {

        $fields = 'categories.id as cat_id, categories.title as cat_title, categories.slug as cat_slug, video_categories.video_id as id, categories.is_active as isActive, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.poster_image, videos.created_at';
        
        $result = Category::join('video_categories', 'video_categories.category_id', '=', 'categories.id')
        ->join('videos', 'videos.id', '=', 'video_categories.video_id')
        ->selectRaw($fields)
        ->whereIn('categories.id', $categoryArray)
        ->where('categories.is_active',1)
        ->where('videos.is_live', 0)
        ->where('videos.is_active', 1)->where('videos.job_status', '=', 'Complete')->where('videos.is_archived', 0)->where('videos.is_adult', 0)
        ->where('videos.title', 'like', '%' . $search . '%')
        ->orderBy('category_order', 'asc')
        ->orderBy('categories.title', 'asc')->get()->toArray();
        
        if (!empty($result) && @count($result) > 0) {
          foreach ($result as $i => $item) {
            //echo '<pre>';print_r($item);exit;
            $data['id'] = $item['cat_id'];
            $data['title'] = $item['cat_title'];
            $data['slug'] = $item['cat_slug'];
            $data['type'] = 'category';
            $data['country_id'] = 0;
            $data['isActive'] = $item['isActive'];
            // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
            // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
            // $item['episode_title'] = '';$item['series_title'] = '';
            $item['country_id'] = 0;
            //$result[$i] = $item;
            $data['video_list']['data'] = $result;
            // if(!empty($item)){
            //   foreach($item as $j => $item2) {
            //     $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item2['slug']."' and cw.user_id ='".$user_id."'");
            //     $item2['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
            //     $item2['custom_title'] = '';
            //     $item2['country_id'] = 0;
            //     //echo '<pre>';print_r($item2);
            //     $result[$j] = $item2;
            //   }
            // }
            //$data['video_list']['data'] = $result;
            $data['video_list']['to'] = @count($result);
            $data['video_list']['total'] = @count($result);
            $data['video_list']['current_page'] = 1;
          }
          return $data;
        }else{
          $data['id'] = '';
          $data['title'] = '';
          $data['slug'] = '';
          $data['type'] = 'category';
          $data['video_list']['data'] = [];
          $data['video_list']['to'] = 0;
          $data['video_list']['total'] = 0;
          $data['video_list']['current_page'] = 1;
          return $data;
        }
        return $result;
      } else {
        // return Category::selectRaw('*, "category" AS type, categories.is_active as isActive, id as video_list')->whereIn('id', $categoryArray)->where('categories.is_active',1)->orderBy('category_order', 'asc')->orderBy('title', 'asc')->get()->toArray();
        $result = Category::selectRaw('*, "category" AS type, categories.is_active as isActive, id as video_list')->whereIn('id', $categoryArray)->where('categories.is_active',1)->orderBy('category_order', 'asc')->orderBy('title', 'asc')->get()->toArray();
        //echo '<pre>';print_r($result);exit;
        if(!empty($result)) {
           foreach($result as $i => $item){
              foreach($item['video_list']['data'] as $j => $item2) {
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item2['slug']."' and cw.user_id ='".$user_id."'");
              //     $item2['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              //     $item2['episode_title'] = '';$item2['series_title'] = '';
                  $item2['country_id'] = 0;
                  $result[$i]['video_list']['data'][$j] = $item2;
           }
         }
        }
        return $result;
      }
    });
  }

  public function fetchGenreVideos($genreArray, $search = '')
  {
    $tempArray = (!is_array($genreArray)) ? $genreArray->toArray() : $genreArray;
    $catInfo = !empty($tempArray) ? implode('#', $tempArray) : '0';
    return app('cache')->tags([getCacheTag(), 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_genre_videos_' . $catInfo, getCacheTime(), function () use ($genreArray, $search) {
      $inputArray = $this->request->all();
      if(isset($inputArray['perpage']) && !empty($inputArray['perpage'])) {
        config()->set('access.perpage', $inputArray['perpage']);
      }
      if (!empty($search)) {
        $fields = 'groups.id as grp_id, groups.name as grp_title, groups.name as grp_name, groups.slug as grp_slug, groups.group_image,groups.is_active as group_is_active, collections_videos.video_id as id,videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';

        $result = Group::join('collections_videos', 'collections_videos.group_id', '=', 'groups.id')
          ->join('videos', 'videos.id', '=', 'collections_videos.video_id')
          ->selectRaw($fields)
          ->whereIn('groups.id', $genreArray)
          ->where('groups.is_active', 1)
          ->where('videos.is_live', 0)
          ->where('videos.is_active', 1)->where('videos.job_status', '=', 'Complete')->where('videos.is_archived', 0)->where('videos.is_adult',0)
          ->where('videos.title', 'like', '%' . $search . '%')
          ->orderBy('groups.order', 'asc')->orderBy('groups.name', 'asc')->get()->toArray();
              

        if (!empty($result) && @count($result) > 0) {
          foreach ($result as $item) {
            $data['id'] = $item['grp_id'];
            $data['title'] = $item['grp_title'];
            $data['name'] = $item['grp_name'];
            $data['slug'] = $item['grp_slug'];
            $data['type'] = 'genre';
            $data['isActive'] = $item['is_active'];
            $data['group_image'] = $item['group_image'];
            $data['video_list']['data'] = $result;
            $data['video_list']['to'] = @count($result);
            $data['video_list']['total'] = @count($result);
            $data['video_list']['current_page'] = 1;
          }
          return $data;
        }else{
          $data['id'] = '';
          $data['title'] = '';
          $data['name'] = '';
          $data['slug'] = '';
          $data['type'] = '';
          $data['group_image'] = '';
          $data['video_list']['data'] = [];
          $data['video_list']['to'] = 0;
          $data['video_list']['total'] = 0;
          $data['video_list']['current_page'] = 1;
          return $data;
        }
        return $result;
      } else {
        return Group::selectRaw('*,groups.name as title,"genre" AS type, groups.is_active as isActive, id as video_list')->whereIn('id', $genreArray)->where('groups.is_active', 1)->orderBy('order', 'asc')->orderBy('name', 'asc')->get()->toArray();
      }
    });
  }

  public function fetchGenreVideosMobile($genreArray, $search = '')
  {
    $tempArray = (!is_array($genreArray)) ? $genreArray->toArray() : $genreArray;
    $catInfo = !empty($tempArray) ? implode('#', $tempArray) : '0';
    return app('cache')->tags([getCacheTag(), 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_genre_videos_' . $catInfo, getCacheTime(), function () use ($genreArray, $search) {
      $inputArray = $this->request->all();
      if(isset($inputArray['perpage']) && !empty($inputArray['perpage'])) {
        config()->set('access.perpage', $inputArray['perpage']);
      }
      if (!empty($search)) {
        $fields = 'groups.id as grp_id, groups.name as grp_title, groups.name as grp_name, groups.slug as grp_slug, groups.group_image,groups.is_active as group_is_active, collections_videos.video_id as id,videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.presenter, videos.director, videos.imdb_rating, videos.releaseYear, videos.created_at';

        $result = Group::join('collections_videos', 'collections_videos.group_id', '=', 'groups.id')
          ->join('videos', 'videos.id', '=', 'collections_videos.video_id')
          ->selectRaw($fields)
          ->whereIn('groups.id', $genreArray)
          ->where('videos.is_live', 0)
          ->where('videos.is_active', 1)->where('videos.job_status', '=', 'Complete')->where('videos.is_archived', 0)->where('videos.is_adult',0)
          ->where('videos.title', 'like', '%' . $search . '%')
          ->orderBy('groups.order', 'asc')->orderBy('groups.name', 'asc')->get()->toArray();
              

        if (!empty($result) && @count($result) > 0) {
          foreach ($result as $item) {
            $data['id'] = $item['grp_id'];
            $data['title'] = $item['grp_title'];
            $data['name'] = $item['grp_name'];
            $data['slug'] = $item['grp_slug'];
            $data['type'] = 'genre';
            $data['isActive'] = $item['is_active'];
            $data['group_image'] = $item['group_image'];
            $data['video_list']['data'] = $result;
            $data['video_list']['to'] = @count($result);
            $data['video_list']['total'] = @count($result);
            $data['video_list']['current_page'] = 1;
          }
          return $data;
        }else{
          $data['id'] = '';
          $data['title'] = '';
          $data['name'] = '';
          $data['slug'] = '';
          $data['type'] = '';
          $data['group_image'] = '';
          $data['video_list']['data'] = [];
          $data['video_list']['to'] = 0;
          $data['video_list']['total'] = 0;
          $data['video_list']['current_page'] = 1;
          return $data;
        }
        return $result;
      } else {
        return Group::selectRaw('*,groups.name as title,"genre" AS type, groups.is_active as isActive, id as video_list')->whereIn('id', $genreArray)->orderBy('order', 'asc')->orderBy('name', 'asc')->get()->toArray();
      }
    });
  }

  public function fetchMoreCategoryVideos()
  {
    $result = [];
    $this->setRules([
      'type' => 'required|in:trending,popular,category,genre,new,all',
      'category' => 'required_if:type,in:category,trending',
      'genre' => 'required_if:type,genre',
    ]);
    $this->validate($this->request, $this->getRules());

    $inputArray = $this->request->all();
    $catName    = !empty($inputArray['category']) ? $inputArray['category'] : '';
    $genre      = !empty($inputArray['genre']) ? $inputArray['genre'] : '';
    $type       = $inputArray['type'];
    
    return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories', 'watch_history'])->remember(getCacheKey(1) . '_category_more_cat' . $catName . '_gen' . $genre . '_type' . $type, getCacheTime(), function () use ($type, $inputArray) {
      $search  = !empty($inputArray['search']) ? str_replace("%20", ' ', $inputArray['search']) : '';
      $is_mobile = !empty($inputArray['is_mobile']) ? $inputArray['is_mobile'] : 0;
      switch ($type) {
        case $type == 'category':
          $categoryInfo = $this->fetchChildrens(false);
          $categoryArray = $this->fetchChildrens(true, $categoryInfo);
          if (!empty($categoryInfo)) {
            $result = $this->fetchSubCategoryVideos([$categoryInfo['id']], $search);

            if (empty($search)) {
              // To convert Array into single object in response format
              $result = !empty($result[0]) ? $result[0] : [];
            }
          }
          break;
        case $type == 'genre':
          $categoryInfo   = $this->fetchChildrens(false);
          $genreArray     = $this->fetchGenre($categoryInfo, 1);
          $genreInfo = Group::where($this->getKeySlugorId(), $inputArray['genre'])->first();
          if (!empty($genreInfo)) {
            if(!empty($is_mobile) && $is_mobile == 1) {
              $result = $this->fetchGenreVideosMobile([$genreInfo['id']], $search);
            } else{
              $result = $this->fetchGenreVideos([$genreInfo['id']], $search);
            }
            
            if (empty($search)) {
              // To convert Array into single object in response format
              $result = !empty($result[0]) ? $result[0] : [];
            }
          }
          break;
        case $type == 'trending':
          $categoryArray    = $this->fetchChildrens();
          $result = $this->getTrendingVideos($categoryArray);
          $result = $this->formatCatVideos('trending', $result);
          break;
        case $type == 'popular':

          $video  = $this->video->whereCustomer();
          $categoryArray    = $this->fetchChildrens();
          $result = $this->fetchPopularVideos($video, $categoryArray);

          $result = $this->formatCatVideos('popular', $result);
          break;
        case $type == 'new':
            $video  = $this->video->whereCustomer();
            $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';
            $categoryArray    = $this->fetchChildrens();
            $video = $this->fetchNewVideos($fields, $video, $categoryArray, $search);
            //$video = $video;
            $result = $this->formatCatVideos('new', $video);
            $video['category_name'] = trans('general.new_videos');
            break;
        case $type == 'all':
              $video  = $this->video->whereCustomer();
              $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';
              $categoryArray    = $this->fetchChildrens();
              $video = $this->fetchAllVideos($fields, $video, $categoryArray, $search);
              $result = $this->formatCatVideos('all', $video);
              //$video['category_name'] = trans('general.new_videos');
              break;
        default:
          $video  = $this->video->whereCustomer();
          $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';
          $categoryArray    = $this->fetchChildrens();
          $video = $this->fetchNewVideos($fields, $video, $categoryArray, $search);
          $video = $video->toArray();
          $result = $this->formatCatVideos('new', $video);
          $video['category_name'] = trans('general.new_videos');
          break;
      }

      $videoInfo['more_category_videos'] = $result;
      return $videoInfo;
    });
  }

  //for mobile --- vinod is_Active = 0 for genre and category

  public function fetchMoreCategoryVideosMobile()
  {
    $result = [];
    $this->setRules([
      'type' => 'required|in:trending,popular,category,genre,new,all',
      'category' => 'required_if:type,in:category,trending',
      'genre' => 'required_if:type,genre',
    ]);
    $this->validate($this->request, $this->getRules());

    $inputArray = $this->request->all();
    $catName    = !empty($inputArray['category']) ? $inputArray['category'] : '';
    $genre      = !empty($inputArray['genre']) ? $inputArray['genre'] : '';
    $type       = $inputArray['type'];
    
    return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories', 'watch_history'])->remember(getCacheKey(1) . '_category_more_cat' . $catName . '_gen' . $genre . '_type' . $type, getCacheTime(), function () use ($type, $inputArray) {
      $search  = !empty($inputArray['search']) ? str_replace("%20", ' ', $inputArray['search']) : '';
      $user_id  = !empty($inputArray['user_id']) ? str_replace("%20", ' ', $inputArray['user_id']) : '';
      switch ($type) {
        case $type == 'category':
          $categoryInfo = $this->fetchChildrens(false);
          $categoryArray = $this->fetchChildrens(true, $categoryInfo);
          if (!empty($categoryInfo)) {
            $result = $this->fetchSubCategoryVideos([$categoryInfo['id']], $search);
            if (empty($search)) {
              // To convert Array into single object in response format
              $result = !empty($result[0]) ? $result[0] : [];
            }
          }
          break;
        case $type == 'genre':
          $categoryInfo   = $this->fetchChildrens(false);
          
          $genreArray     = $this->fetchGenre($categoryInfo, 1);
          $genreInfo = Group::where($this->getKeySlugorId(), $inputArray['genre'])->first();
          if (!empty($genreInfo)) {
            $result = $this->fetchGenreVideos([$genreInfo['id']], $search);
            if (empty($search)) {
              // To convert Array into single object in response format
              $result = !empty($result[0]) ? $result[0] : [];
            }
          }
          break;
        case $type == 'trending':
          $categoryArray    = $this->fetchChildrens();
          $result = $this->getTrendingVideos($categoryArray);
          $result = $this->formatCatVideos('trending', $result);
          break;
        case $type == 'popular':

          $video  = $this->video->whereCustomer();
          $categoryArray    = $this->fetchChildrens();
          $result = $this->fetchPopularVideos($video, $categoryArray);
          if(!empty($result)){
            foreach($result['data'] as $k => $item){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
              // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item['episode_title'] = '';$item['series_title'] = '';
              $item['country_id'] = 0;
              $result['data'][$k] = $item;
            }
          }
          $result = $this->formatCatVideos('popular', $result);
          break;
        case $type == 'new':
            $video  = $this->video->whereCustomer();
            $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';
            $categoryArray    = $this->fetchChildrens();
            $video = $this->fetchNewVideos($fields, $video, $categoryArray, $search);
            //$video = $video;
            if(!empty($video)){
              foreach($video['data'] as $k => $item){
                // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
                // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
                // $item['episode_title'] = '';$item['series_title'] = '';
                $item['country_id'] = 0;
                $video['data'][$k] = $item;
              }
            }
            $result = $this->formatCatVideos('new', $video);
            $video['category_name'] = trans('general.new_videos');
            break;
        case $type == 'all':
              $video  = $this->video->whereCustomer();
              $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';
              $categoryArray    = $this->fetchChildrens();
              $video = $this->fetchAllVideos($fields, $video, $categoryArray, $search);
              if(!empty($video)){
                foreach($video['data'] as $k => $item){
                  // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
                  // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
                  // $item['episode_title'] = '';$item['series_title'] = '';
                  $item['country_id'] = 0;
                  $video['data'][$k] = $item;
                }
              }
              $result = $this->formatCatVideos('all', $video);
              //$video['category_name'] = trans('general.new_videos');
              break;
        default:
          $video  = $this->video->whereCustomer();
          $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';
          $categoryArray    = $this->fetchChildrens();
          $video = $this->fetchNewVideos($fields, $video, $categoryArray, $search);
          $video = $video->toArray();
          if(!empty($video)){
            foreach($video['data'] as $k => $item){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
              // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item['episode_title'] = '';$item['series_title'] = '';
              $item['country_id'] = 0;
              $video['data'][$k] = $item;
            }
          }
          $result = $this->formatCatVideos('new', $video);
          $video['category_name'] = trans('general.new_videos');
          break;
      }

      $videoInfo['more_category_videos'] = $result;
      return $videoInfo;
    });
  }

  public function formatCatVideos($type, $result, $categoryInfo = [])
  {
    $final['video_list'] = $result;

    $title = trans('video::videos.new');
    if ($type == 'trending') {
      $title = trans('video::videos.trending');
    } else if ($type == 'popular') {
      $title = trans('video::videos.popular');
    } else if ($type == 'all') {
      $title = 'All';
    }

    if (!empty($categoryInfo) && $type != 'all') {
      $title .= ($type == 'new') ? ' ' . trans('video::videos.on') . ' ' . $categoryInfo->title : ' ' . trans('video::videos.in') . ' ' . $categoryInfo->title;
    }

    $final['title']    = $title;
    $final['type']     = $type;
    $final['id']     = ($this->request->has('category')) ? (isMobile() ? (int) $this->request->category : $this->request->category) : '';
    $final['isActive']   = 1;
    return $final;
  }

  public function fetchSeriesVideos()
  {
    $this->setRules(['category' => 'required']);
    $this->validate($this->request, $this->getRules());


    $categoryArray = [];
    $inputArray = $this->request->all();
    $video = $this->video->whereCustomer();

    $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.thumbnail_image, videos.hls_playlist_url, videos.poster_image,videos.is_live,videos.view_count';

    $categoryInfo = $this->fetchChildrens(false);
    $categoryArray = $this->fetchChildrens(true, $categoryInfo);
    $genreArray = $this->fetchGenre($categoryInfo);

    $new = $this->fetchNewVideos($fields, $video, $categoryArray);
    $new = $this->formatCatVideos('new', $new);

    $popular = $this->fetchPopularVideos($video, $categoryArray);
    $popular = $this->formatCatVideos('popular', $popular);

    $videoInfo['main'] = [];
    $videoInfo['main'][] = $popular;
    $videoInfo['main'][] = $new;

    $videoInfo['genre_videos'] = $this->fetchGenreVideos($genreArray);
    if (!$categoryInfo->is_web_series) {
      $videoInfo['category_videos'] = $this->fetchSubCategoryVideos($categoryArray);
      $videoInfo['web_series'] = 0;
    } else {
      $videoInfo['web_series'] = 1;
      $videoInfo['category_videos'] = [];
    }

    return $videoInfo;
  }


  public function fetchRecommendedVideos()
  {
    return app('cache')->tags([getCacheTag(), 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_more_live_videos', getCacheTime(), function () {
      $fields = 'videos.id, videos.title, videos.slug, videos.description, videos.thumbnail_image, videos.hls_playlist_url, videos.id as is_favourite, videos.id as collection, videos.poster_image,videos.is_live, videos.scheduledStartTime,videos.is_premium, videos.view_count';
      $result = array();
      try {
        $result['error']    = false;
        $result['message']  = '';
        $result['data']     = $this->video->whereIn('slug', $this->request->slug_list)->where('job_status', 'Complete')->where('is_archived', 0)->where('is_active', 1)->selectRaw($fields)->orderBy('scheduledStartTime', 'asc')->paginate(config('access.perpage'));
      } catch (\Exception $e) {
        $result['error'] = true;
        $result['message']    = $e->getMessage();
      }
      return $result;
    });
  }

  //get all VOD dashboard (NEW,POPULAR,GENRE,CATEGORY)-- vinod

  public function fetchVODDashboard()
  {
    $this->setRules(['category' => 'required']);
    $this->validate($this->request, $this->getRules());

    $inputArray = $this->request->all();
    
    $section = 1;
    $catName = 'movies';
    $series = 0;
    $search = !empty($inputArray['search']) ? str_replace("%20", ' ', $inputArray['search']) : '';
    $user_id = !empty($inputArray['user_id']) ? $inputArray['user_id'] : 0;
    return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories', 'watch_history','continue_watch_history'])->remember(getCacheKey(1) . '_category_info_cat' . $catName . '_series' . $series . '_section' . $section, getCacheTime(), function () use ($section, $search, $user_id) {

      $categoryArray = [];

      $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.created_at';

      $categoryInfo   = $this->fetchChildrens(false);
      $categoryArray  = $this->fetchChildrens(true, $categoryInfo);

      $a = DB::select("SELECT `id` FROM `categories` WHERE `title` = 'Restricted'");
      foreach ($categoryArray as $key => $value) {
        if ($value == $a[0]->id) {
          unset($categoryArray[$key]);
        }
      }

      $section = 1;
      $video  = $this->video->whereCustomer();
      $new    = $this->fetchNewVideos($fields, $video, $categoryArray, $search);
      if(!empty($new)){
        foreach($new['data'] as $k => $item){
          // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
          // $item['current_duration'] = (!empty($cw_info) && $cw_info['duration']!='') ? $cw_info['duration'] : 0;
          // $item['episode_title'] = '';$item['series_title'] = '';
          $item['country_id'] = 0;
          $new['data'][$k] = $item;
        }
      }
      $new    = $this->formatCatVideos('new', $new, $categoryInfo);

      $video    = $this->video->whereCustomer();
      $popular  = $this->fetchPopularVideos($video, $categoryArray, $fields, $search);
      if(!empty($popular)){
        foreach($popular['data'] as $j => $item2){
          // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item2['slug']."' and cw.user_id ='".$user_id."'");
          // $item2['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
          // $item2['episode_title'] = '';$item2['series_title'] = '';
          $item2['country_id'] = 0;
          $popular['data'][$j] = $item2;
        }
      }
      $popular  = $this->formatCatVideos('popular', $popular, $categoryInfo);

      $videoInfo['main'][] = $new;
      $videoInfo['main'][] = $popular;

      //FOR SECTION = 2

      $genreArray     = $this->fetchGenre($categoryInfo);
      $genre = $this->fetchGenreVideos($genreArray);
            
      foreach ($genre as $item) {
        $videoInfo['main'][] = $item;
      }

      if (!$categoryInfo->is_web_series) {
        $category = $this->fetchSubCategoryVideos($categoryArray, $search);
        foreach ($category as $i => $cat_item) {
           if(!empty($cat_item['video_list']['data'])) {
            foreach($cat_item['video_list']['data'] as $i => $item){
              // $cw_info = DB::select("select cw.duration from `continue_watch_history` as cw join `videos` as v ON v.id = cw.video_id where v.slug = '".$item['slug']."' and cw.user_id ='".$user_id."'");
              // $item['current_duration'] = (!empty($cw_info)) ? $cw_info['duration'] : 0;
              // $item['episode_title'] = '';$item['series_title'] = '';
              $item['country_id'] = 0;
              $cat_item['video_list']['data'][$i] = $item;
          }
        }
          $videoInfo['main'][] = $cat_item;
        }
      }

      return $videoInfo;
    });
  }

  //get all live tv videos dashboard (app)-- vinod

  public function fetchLiveTVDashboard()
  {

    return app('cache')->tags([getCacheTag(), 'banners', 'videos', 'categories', 'groups', 'collections_videos', 'video_categories'])->remember(getCacheKey() . '_live_videos', getCacheTime(), function () {
      try {
        $result['error']    = false;
        $result['message']  = '';
        $result['data']  = '';

        $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.hls_playlist_url,videos.xmltv_id, videos.custom_siteid, videos.is_live, videos.is_adult';

        if (($this->request->has('country_id') && !empty($this->request->country_id)) && ($this->request->has('category') && !empty($this->request->category))) {
          $videos = $this->video->whereliveVideos()->whereRaw('scheduledStartTime < "' . Carbon::now()->now() . '" ')->orderBy('videos.title', 'ASC')->whereHas('countries', function ($query) {
            $query->where('countries.id', $this->request->country_id)->where('countries.code','!=','FA');
          })->whereHas('categories', function ($query) {
            $query->where('categories.' . $this->getKeySlugorId(), $this->request->category);
          })->selectRaw($fields);
        } else if (($this->request->has('country_id') && !empty($this->request->country_id))) {
          $videos = $this->video->whereliveVideos()->whereRaw('scheduledStartTime < "' . Carbon::now()->now() . '" ')->orderBy('videos.title', 'ASC')->whereHas('countries', function ($query) {
            $query->where('countries.id', $this->request->country_id)->where('countries.code','!=','FA');
          })->selectRaw($fields);
        } else {
          $videos = $this->video->whereliveVideos()->whereRaw('scheduledStartTime < "' . Carbon::now()->now() . '" ')->orderBy('videos.title', 'ASC')->whereHas('countries', function ($query) {
            $query->where('countries.code','!=','FA');
          })->selectRaw($fields);
        }

        $videoObj = new Video();
        if ($this->request->has('perPage') && !empty($this->request->perPage)){
          $perPage = $this->request->perPage;
        }else{
          $perPage = 1000;
        }

        if (($this->request->has('search') && !empty($this->request->search))) {
         // $videos = $videos->where('title', 'like', '%' . $this->request->search . '%');
          $videos = $this->constructSearchQuery($videos);
        }
        $perPage = 1000;
        $livetv_videos = $videos->paginate($perPage)->toArray();
        //$livetv_videos = $videos->paginate($perPage)->toArray();
        
        } catch (\Exception $e) {
          exit;
          $livetv_videos = [];
      }
      $livetv_videos['title'] = 'Live TV';

      return $livetv_videos;
    });
  }

  public function gettopList($value = '')
  {
    // top Categories  - Movies , Series,  TV , Sports

    $cat = array('Movies', 'Series', 'Channels', 'Sports', 'Kids', 'News');
    $languages_slug = array('hollywood','bollywood','kollywood','tollywood');
    $language_data = [];
    foreach($languages_slug as $key => $lang) {
      $languages = Category::selectRaw('id, slug')->where(['slug' => $lang,'is_deletable' => 1, 'is_active' => 1])->get();
      $title = '';
      foreach($languages as $key1 => $item) {
        if($item['slug'] == 'hollywood') {$title ='English';}
        if($item['slug'] == 'bollywood') {$title ='Hindi';}
        if($item['slug'] == 'kollywood') {$title ='Tamil';}
        if($item['slug'] == 'tollywood') {$title ='Telugu';}
        $language_data[] = array('id' => $item['id'], 'name' => $title, 'slug' => $item['slug'], 'type' => 'category');
      }
    }
    $toplist['category'] = $cat;
    $toplist['genre'] = Group::selectRaw('id, name, slug, "genre" AS type')->where('slug','!=','adult')->get()->toArray();
    $toplist['languages'] = $language_data;
    return $toplist;
  }

  public function getCountryWiseTV($c_id = '', $cat_id = '', $web ='')
  {
    if(!empty($this->request->search)){
      $search = $this->request->search;
    }else{
        $search = '';    
    }

    if(!empty($this->request->is_newversion)){
      $is_newversion = $this->request->is_newversion;
    }else{
      $is_newversion = '';    
    }

    //Log::info('newversion'.$is_newversion);
    if(!empty($search)){
      

      if(!empty($cat_id)){
        $a = "SELECT cat.id, cat.title, cat.slug, c.code, GROUP_CONCAT(v.id) as ids FROM country_categories cc JOIN categories cat ON cc.category_id=cat.id JOIN videos v ON cc.video_id=v.id JOIN countries c ON cc.country_id= c.id WHERE c.is_active = 1 and  v.is_live = 1 and v.is_active = 1 and v.job_status='Complete' and v.is_archived = 0 and v.liveStatus != 'complete' AND (cat.is_live = 1) and cat.is_active = 1";
        if (empty($web)) {
          $a .= " AND is_adult = 0 AND (c.code != 'FA')"; 
        }
        //is_adult = 0 AND (c.code != 'FA') AND 
        if (!empty($c_id)) {
          $a .= " AND ( cc.country_id = $c_id) ";
        }
        if (!empty($cat_id)) {
          $a .= " AND ( cat.id = $cat_id) ";
        }
        $a .= " group by (cc.category_id) ";
        $a =   DB::select($a);
        $i = 0;$j=0;
        $b = array();


        if(!empty($a)){
            foreach ($a as $key => $value) {
              if (!empty($cat_id)) { 
                if($value->id != $cat_id) continue;
              }
              if ($value->title == 'Restricted' && $web == '') {
                continue;
              }
              $value->ids = explode(',', $value->ids);
              $value->ids = implode(',', $value->ids);
              $value->ids = rtrim($value->ids, ',');
              
              $livetv_data = DB::select("SELECT videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.hls_playlist_url, videos.is_live, videos.is_adult, videos.xmltv_id,videos.custom_siteid from videos  WHERE is_live = 1 and is_active = 1 and job_status='Complete' and is_archived = 0 and liveStatus != 'complete' AND ( id IN ($value->ids)) AND title like '%$search%' ORDER BY title ASC");
              
              foreach($livetv_data as $j => $item) {
                if($is_newversion) {
                  $item->hls_playlist_url = $this->url_encryptor('encrypt',$item->hls_playlist_url);
                }
                $country_info = DB::select("select country_id from `country_categories` where video_id = $item->id");
                $item->country_id = $country_info[0]->country_id;
                $b[$i]['data'][] = $item;
              }

              $b[$i]['title'] = '';
              $b[$i]['slug'] = '';
              $b[$i]['id'] = '';
              $i++;
          }
        }
      }else{
        $i = 0;
        $b = array();
        $livetv_search = $this->fetchLiveTVDashboard();
        foreach($livetv_search['data'] as $k => $item3) {
          if($is_newversion) {
            $item3['hls_playlist_url'] = $this->url_encryptor('encrypt',$item3['hls_playlist_url']);
          }
          $country_info = DB::select("select cc.country_id from `country_categories` as cc join  `videos` as v ON v.id = cc.video_id where v.slug = '".$item3['slug']."'");
            $item3['country_id'] = $country_info[0]->country_id;
            $livetv_search['data'][$k] = $item3;
        }
        $b[$i] = $livetv_search;
        $b[$i]['title'] = '';
        $b[$i]['slug'] = '';
        $b[$i]['id'] = '';
      }
      
    }else {
      $a = "SELECT cat.id, cat.title, cat.slug, c.code, GROUP_CONCAT(v.id) as ids FROM country_categories cc JOIN categories cat ON cc.category_id=cat.id JOIN videos v ON cc.video_id=v.id JOIN countries c ON cc.country_id= c.id WHERE c.is_active = 1 and  v.is_live = 1 and v.is_active = 1 and v.job_status='Complete' and v.is_archived = 0 and v.liveStatus != 'complete' AND (cat.is_live = 1) and cat.is_active = 1";
      if (empty($web)) {
        $a .= " AND is_adult = 0 AND (c.code != 'FA')"; 
      }
      //is_adult = 0 AND (c.code != 'FA') AND 
      if (!empty($c_id)) {
        $a .= " AND ( cc.country_id = $c_id) ";
      }
      $a .= " group by (cc.category_id) ";
      $a =   DB::select($a);
      $i = 0;$j=0;
      $b = array();


      if(!empty($a)){
          foreach ($a as $key => $value) {
            if (!empty($cat_id)) { 
              if($value->id != $cat_id) continue;
            }
            if ($value->title == 'Restricted' && $web == '') {
              continue;
            }
            $value->ids = explode(',', $value->ids);
            $value->ids = implode(',', $value->ids);
            $value->ids = rtrim($value->ids, ',');

            $livetv_data = DB::select("SELECT videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.hls_playlist_url, videos.is_live, videos.is_adult, videos.xmltv_id,videos.custom_siteid from videos  WHERE is_live = 1 and is_active = 1 and job_status='Complete' and is_archived = 0 and liveStatus != 'complete' AND ( id IN ($value->ids)) ORDER BY title ASC");
            
            foreach($livetv_data as $j => $item) {
              if($is_newversion) {
                $item->hls_playlist_url = $this->url_encryptor('encrypt',$item->hls_playlist_url);
              }
              $country_info = DB::select("select country_id from `country_categories` where video_id = $item->id");
              $item->country_id = $country_info[0]->country_id;
              $b[$i]['data'][] = $item;
            }

            $b[$i]['title'] = $value->title;
            $b[$i]['slug'] = $value->slug;
            $b[$i]['id'] = $value->id;
            $i++;
        }
      }

      if (empty($cat_id)) {
        $videos = $this->fetchLiveTVDashboard();
        foreach($videos['data'] as $j => $item2) {
          if($is_newversion) {
            $item2['hls_playlist_url'] = $this->url_encryptor('encrypt',$item2['hls_playlist_url']);
          }
          $country_info = DB::select("select cc.country_id from `country_categories` as cc join  `videos` as v ON v.id = cc.video_id where v.slug = '".$item2['slug']."'");
            $item2['country_id'] = $country_info[0]->country_id;
            $b[$i]['data'][] = $item2;
            $b[$i]['current_page'] = $videos['current_page'];
            $b[$i]['next_page_url'] = $videos['next_page_url'];
            $b[$i]['total'] = $videos['total'];
        }
        
        // //$b[$i][] = $videos;
         $b[$i]['title'] = 'ALL';
         $b[$i]['slug'] = 'all';
         $b[$i]['id'] = '';
      }
    }

    return $b;
  }

  //get the categories by country wise

  public function getCategoriesCountryWise($c_id = '', $type = '')
  {
    if($type == 1) {
      $a = "SELECT cat.id, cat.title, cat.slug FROM country_categories cc JOIN categories cat ON cc.category_id=cat.id JOIN videos v ON cc.video_id=v.id JOIN countries c ON cc.country_id= c.id WHERE c.is_active = 1 and  v.is_live = 1 and v.is_active = 1 and v.job_status='Complete' and v.is_archived = 0 and v.liveStatus != 'complete' AND is_adult = 0 AND (c.code != 'FA') AND (cat.is_live = 1) and cat.is_active = 1";
      if (!empty($c_id)) {
        $a .= " AND ( cc.country_id = $c_id) ";
      }
      
      $a .= " group by (cc.category_id) ";
      $a .= " Order by (cat.title) ";
      $a =   DB::select($a);
      $i = 0;
      $b = array();

      $b[$i]['data'] = $a; 
      $b[$i]['title'] = 'categories';
      $b[$i]['slug'] = '';
      $b[$i]['id'] = '';
    }else{

     $a = "SELECT 'category' as type,c.title,c.slug,count(v.id) as counts FROM categories c LEFT JOIN video_categories vc ON c.`id`=vc.`category_id` LEFT JOIN videos v ON vc.`video_id`=v.`id` where c.`parent_id` = 1 and c.`is_live` = 0 and c.`is_active` = 1 and v.`is_active` = 1 and v.is_live = 0 and  v.`job_status`='Complete' and v.`is_archived` = 0 and v.`liveStatus` != 'complete' ";
            
      $a .= " group by (c.id) ";
      $a .= " Order by (c.title) ASC";
      $a =   DB::select($a);
      $i = 0;
      $b = array();

      $b[$i]['data'] = $a; 
      $b[$i]['title'] = 'categories';
      $b[$i]['slug'] = '';
      $b[$i]['id'] = '';
    }
      
    return $b;
  }

  //get the categories by country wise

  public function getLivesimilarVideos($cat_id = '')
  {

      $a = "SELECT cat.id, cat.title, cat.slug, GROUP_CONCAT(v.id) as ids FROM country_categories cc JOIN categories cat ON cc.category_id=cat.id JOIN videos v ON cc.video_id=v.id JOIN countries c ON cc.country_id= c.id WHERE c.is_active = 1 and  v.is_live = 1 and v.is_active = 1 and v.job_status='Complete' and v.is_archived = 0 and v.liveStatus != 'complete' AND (cat.is_live = 1) and cat.is_active = 1 AND cat.id ='".$cat_id."'";
      $a .= " group by (cc.category_id) ";
      $a =   DB::select($a);
      $i = 0;$j=0;
      $b = array();

      if(!empty($a)){
        foreach ($a as $key => $value) {
          // if ($value->title == 'Restricted') {
          //   continue;
          // }
          $value->ids = explode(',', $value->ids);
          $value->ids = implode(',', $value->ids);
          $value->ids = rtrim($value->ids, ','); 

          $b['data'] = DB::select("SELECT videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live, videos.is_adult from videos  WHERE is_live = 1 and is_active = 1 and job_status='Complete' and is_archived = 0 and liveStatus != 'complete' AND ( id IN ($value->ids)) ORDER BY title ASC limit 10");
          
          $b['title'] = $value->title;
          $b['slug'] = $value->slug;
          $b['id'] = $value->id;
         
      }
    }
    return $b;
  }

  //get the continue watch list

  public function getContinuewatchList()
  {
    $watchlist  = array(); 
       
    if(!empty($this->request->user_id)){
      $user_id = $this->request->user_id;
    }else{
      $user_id = '';    
    }

    if(!empty($this->request->is_series)){
      $is_series = $this->request->is_series;
    }else{
      $is_series = 0;
    }

    if(!empty($this->request->is_newversion)){
      $is_newversion = $this->request->is_newversion;
    }else {
      $is_newversion = '';
    }

    if (!empty($this->request->search)) {
      $search = $this->request->search;
      $search  = !empty($search) ? str_replace("%20", ' ', $search) : '';
    } else{
      $search = ''; 
    } 

    $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.poster_image, videos.hls_playlist_url, videos.subtitle, videos.video_duration, continue_watch_history.category_id, continue_watch_history.duration as currentduration, continue_watch_history.is_series, continue_watch_history.title as episode_title,continue_watch_history.series_title,continue_watch_history.episode_name';

    $where = array('continue_watch_history.user_id'=> $user_id, 'c.is_active' => 1,'videos.is_active' => 1, 'videos.is_archived' => 0);
    
    if(!empty($search)) {
      if($is_series == 1){
        $watchlist = ContinueWatchHistory::leftjoin('categories as c','c.id','=','continue_watch_history.category_id')
                                      ->leftjoin('videos','videos.id','=','continue_watch_history.video_id')
                                      ->selectRaw($fields)
                                      ->where('job_status', 'Complete')
                                      ->where('is_adult',0)
                                      ->where($where)
                                      ->where('continue_watch_history.title', 'like', '%' . $search . '%');
      }else{
        $watchlist = ContinueWatchHistory::leftjoin('categories as c','c.id','=','continue_watch_history.category_id')
                                      ->leftjoin('videos','videos.id','=','continue_watch_history.video_id')
                                      ->selectRaw($fields)
                                      ->where('job_status', 'Complete')
                                      ->where('is_adult',0)
                                      ->where($where)
                                      ->where('videos.title', 'like', '%' . $search . '%');
      }
      
    }else{
      $watchlist = ContinueWatchHistory::leftjoin('categories as c','c.id','=','continue_watch_history.category_id')
                                      ->leftjoin('videos','videos.id','=','continue_watch_history.video_id')
                                      ->selectRaw($fields)
                                      ->where('job_status', 'Complete')
                                      ->where('is_adult',0)
                                      ->where($where);
    }
    if($is_series != 2) {
      $watchlist = $watchlist->where('is_series',$is_series);
      //$watchlist = $watchlist->groupBy('c.id');
    }
  
    $watchlist = $watchlist->orderBy('continue_watch_history.updated_at', 'desc');
                         
    if ($this->request->has('perpage') && !empty($this->request->perpage)) {
        $perPage = $this->request->perpage;
    }else {
        $perPage = 60;
    }
    
    $history = array();
  
    //DB::enableQueryLog();
    $history = $watchlist->paginate($perPage)->toArray();
    // $query = DB::getQueryLog();
    // print_r($query);exit;
    $items_list = array();$stack = array();$vod_stack = array();
    if(!empty($history)){
      $i= 0;
      foreach($history['data'] as $item) {
        if($i>=20) continue;
        $item['subtitle'] = $this->getSubtitleAttribute($item['subtitle']);
        if($is_newversion) {
          $item['hls_playlist_url'] = $this->url_encryptor('encrypt', $item['hls_playlist_url']);
        }
        $result = $this->calculatePercentage($item['video_duration'], $item['currentduration']);
        if($result['flag']) {
          $item['percentage'] = $result['percentage'];
          if($item['is_series'] == 1) {
            if (!in_array($item['category_id'], $stack)) {
              array_push($stack,$item['category_id']);
              $items_list[] = $item; 
              $i++;
            }
          }else{
            if (!in_array($item['slug'], $vod_stack)) {
                array_push($vod_stack,$item['slug']);
                $items_list[] = $item; 
                $i++;
            }
          }
        }
      }
      $history['data'] = $items_list;
    }
    
    $history['title'] = 'Continue Watching';
    $history['slug'] = 'continue-watch-list';
    $history['id'] = '';
    
    return ($is_series != 2) ? [$history] : $history;
  }

  public function getSubtitleAttribute($value) {
    $result['base_url'] = env('AWS_BUCKET_URL');
    $result['subtitle_list'] = [];
    if($value != '') {
        $result['subtitle_list'] = json_decode($value);
    }
    return $result;
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


  //update Continue watching list history

  public function updateVideoDuration()
  {
    
    if(!empty($this->request->video_slug)){
      $video_slug = $this->request->video_slug;
    }else{
      $video_slug = '';    
    }
    if(!empty($this->request->user_id)){
      $user_id = (int)$this->request->user_id;
    }else{
      $user_id = '';    
    }
    if(!empty($this->request->duration)){
      $duration = (int)$this->request->duration;
    }else{
      $duration = 0;
    }
    if(!empty($this->request->is_series)){
      $is_series = $this->request->is_series;
    }else{
      $is_series = 0;
    }
    if(!empty($this->request->episode_title)){
      $episode_title = $this->request->episode_title;
    }else{
      $episode_title = '';    
    }

    if(!empty($this->request->series_title)){
      $series_title = $this->request->series_title;
    }else{
      $series_title = '';    
    }

    if(!empty($this->request->episode_name)){
      $episode_name = $this->request->episode_name;
    }else{
      $episode_name = '';    
    }
      //try{
      if($video_slug != '' && $user_id != '' && $duration != ''){
        
        $videos = DB::select("SELECT v.id, cc.country_id, cc.category_id from videos v LEFT JOIN country_categories cc ON v.id = cc.video_id WHERE v.slug = '".$video_slug."'");
        if(!empty($videos)){
          foreach ($videos as $key => $value) {
            $video_id = $value->id;
            $category_id = $value->category_id;
            $country_id = $value->country_id;
          }
        }

        $wcount = DB::table('continue_watch_history')->where(['user_id' => $user_id, 'video_id' => $video_id])->first();

        if(!empty($wcount) && @count($wcount)>0){
          //$previous_duration = $wcount->duration;
          //if($previous_duration > (int)$duration) $duration = $previous_duration;
          $where = array('video_id' => $video_id,'user_id' => $user_id);
          Log::info('sa'.(int)$duration);
          ContinueWatchHistory::where($where)->update(['duration' => (int)$duration, 'updated_at' => date('Y-m-d H:i:s')]);
        }else{
          $data = array('video_id' => $video_id, 'country_id' => $country_id, 'category_id' => $category_id, 'user_id' => $user_id, 'is_series' => $is_series, 'duration' => (int)$duration, 'title' => $episode_title, 'series_title' => $series_title, 'episode_name' => $episode_name,'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));
          Log::info('update duration data'.$episode_title. '----'.$episode_name);  
          $watch_history = DB::table('continue_watch_history')->insert($data);
        }

        $result['error'] = false;
        $result['message'] = trans('video::videos.fetch.success');
      } else{
        $result['error'] = true;
        $result['message'] = trans('video::videos.fetch.error');
      }
      
    // //} catch (\Exception $e) {
    //   $result['error'] = true;
    //   $result['message'] = trans('video::videos.fetch.error');
    // }

    return $result;
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
