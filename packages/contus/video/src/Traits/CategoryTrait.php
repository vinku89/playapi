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
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Traits;

use Contus\Video\Contracts\ICategoryRepository;
use Contus\Video\Models\Category;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\Video;
use Contus\Video\Models\Comment;
use Contus\Video\Models\Collection;
use Contus\Customer\Models\MypreferencesVideo;
use Illuminate\Support\Facades\Cache;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\Webseries;
use Contus\Base\ApiController;
use Carbon\Carbon;
use DB;

trait CategoryTrait
{
    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getChildCategoryEach($value)
    {
        $subcatvalue = array();
        foreach ($value['child_category'] as $newvalue) {
            if (config()->get('auth.providers.users.table') === 'customers') {
                $subcatvalue[$newvalue[$this->getKeySlugorId()]] = $newvalue['title'];
            } else {
                $subcatvalue[$newvalue['id']] = $value['title'] . ' > ' . $newvalue['title'];
            }
        }
        return $subcatvalue;
    }

    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getAllCategoriesSlugs()
    {
        if ($this->request->has('main_category') && !empty($this->request->main_category)) {
            return $this->_category->where('parent_id', 0)->where($this->getKeySlugorId(), $this->request->main_category)->has('child_category.child_category.videos')->where('is_active', 1)->with(['child_category' => function ($query) {
                return $query->has('child_category.videos')->with(['child_category' => function ($query) {
                    return $query->has('videos')->with('videosCount')->orderBy('id', 'desc');
                }])->orderBy('is_leaf_category', 'asc');
            }])->first();
        }
        return $this->_category->where('parent_id', 0)->where('is_active', 1)->has('child_category.child_category.videos')->with(['child_category' => function ($query) {
            return $query->has('child_category.videos')->with(['child_category' => function ($query) {
                return $query->has('videos')->with('videosCount');
            }]);
        }])->get();
    }
    /**
     * Funtion to get related category video with complete information using slug
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function getRelatedVideoSlug($slug, $getCount = 10, $paginate = true)
    {

        $video = new Video();
        $currentVideo = $video->whereCustomer()->where($this->getKeySlugorId(), $slug)->first();
        $genre = $currentVideo->group()->first();

        if (!empty($genre)) {
            $video = new Video();
            // ->where('videos.is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)
            $video = $video->whereCustomer()->where('is_webseries', 0)->selectRaw('videos.id, videos.title, videos.slug, videos.thumbnail_image, videos.is_live')->whereHas('group', function ($query) use ($genre, $currentVideo) {
                $query->where('groups.id', $genre->id)->where('collections_videos.video_id', '!=', $currentVideo->id);
            });
        } else {
            $video = new Video();
            $video = $video->whereCustomer()->where('is_webseries', 0)->where($this->getKeySlugorId(), $slug)->first()->categories();

            if (!empty($video->get()->toArray())) {

                if ($currentVideo->is_live == 1) {
                    return $this->repository->getLiverelatedVideos($slug)->toArray();
                }

                $video = $video->first()->videos()->where('videos.' . $this->getKeySlugorId(), '!=', $slug)->where('is_live', '==', 0)->orderBy('video_order', 'asc');
                $video = $video->where('is_live', '==', 0)->orderBy('video_order', 'asc');
            }
        }
        if ($paginate) {
            $video = $video->paginate($getCount, ['videos.id', 'videos.title', 'videos.slug', 'videos.thumbnail_image', 'videos.is_live']);
        } else {
            $video = ($getCount) ? $video->take($getCount)->get() : $video->get();
        }
        return ($paginate) ? $video->toArray() : $video;
    }
    /**
     * Funtion to get parent category video with complete information using slug
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function getParentCategory($slug)
    {
        return $this->_category->where('level', 0)->where('is_active', 1)->where($this->getKeySlugorId(), $slug)->first()->parent_category()->first()->parent_category()->first();
    }
    /**
     * Funtion to get parent category video with complete information using slug
     *
     * @package video
     * @return array
     */
    public function getChidCategory($slug)
    {
        return $this->_category->where('level', 1)->where('is_active', 1)->where($this->getKeySlugorId(), $slug)->has('child_category.videos')->with(['child_category' => function ($q) {
            return $q->where('is_active', 1)->has('videos')->orderBy('is_leaf_category', 'desc')->with(['videosCount']);
        }])->first();
    }

    /**
     * Funtion to get count of category video with complete information using slug
     *
     * @return array
     */
    public function getChidCategoryCount($slug)
    {
        return $this->_category->where('level', 1)->where('is_active', 1)->where($this->getKeySlugorId(), $slug)->with('child_category')->get()->count();
    }
    /**
     * Funtion to get category for navigation
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function getCategoiesNav($detail = false)
    {
        if ($detail) {
            $return = $this->_category->where('level', 1)->where('is_active', 1)->has('child_category.videos')->with(['parent_category', 'child_category' => function ($q) {
                return $q->where('is_active', 1)->has('videos')->orderBy('is_leaf_category', 'desc')->with('videosCount');
            }])->orderBy('is_leaf_category', 'desc')->get();
        } else {
            $return = $this->_category->where('level', 1)->where('is_active', 1)->has('child_category.videos')->with('parent_category')->take(8)->orderBy('is_leaf_category', 'desc')->get();
            foreach ($return as $k => $v) {
                $return[$k]['child_category'] = $v->child_category()->has('videos')->with('videosCount')->orderBy('is_leaf_category', 'desc')->paginate(11)->toArray();
            }
        }
        return $return;
    }
    /**
     * Function to get all exams by categories
     *
     * @return object
     */
    public function getAllExamsByCategories()
    {
        $collection = new Collection();
        if ($this->request->has('exam_id')) {
            $collection = $collection->where('is_active', 1)->where('slug', $this->request->exam_id)->first()->groups()->has('group_videos')->with(['group_videos' => function ($query) {
                $query->selectRaw('count(videos.id) as count')->groupBy('group_id');
            }])->orderByRaw(' convert(`order`, decimal) desc ')->get();
        } else {
            $collection = $collection->where('is_active', 1)->has('groups')->orderBy('order', 'desc')->get();

            if (count($collection)) {
                foreach ($collection as $k => $v) {
                    $collection[$k]['exams'] = $collection[$k]->groups()->has('group_videos')->with(['group_videos' => function ($query) {
                        $query->selectRaw('count(videos.id) as count')->groupBy('group_id');
                    }])->orderByRaw('convert(`order`, decimal) desc')->get();
                }
            }
        }
        return $collection;
    }

    /**
     * Funtion to get category types and exam types
     *
     * @return array
     */
    public function browsepreferenceListPlaylist()
    {
        $customer_preferences = MypreferencesVideo::where('user_id', $this->authUser->id)->pluck('category_id')->toArray();
        $subcategory = $this->_category->where('is_active', 1)->where('level', 1)->whereNotIn('id', $customer_preferences)->get();
        $exams = Collection::where('is_active', 1)->whereNotIn('id', $customer_preferences)->get();
        if (isset($customer_preferences) || (!empty($subcategory)) && $this->request->header('x-request-type') == 'mobile') {
            return ['sub-categories' => $subcategory, 'exam' => $exams];
        }
    }
    /**
     * Funtion to get all category and exam types
     *
     * @return array
     */
    public function browsepreferenceListAll()
    {
        $subcategory = Category::where('is_active', 1)->where('level', 1)->has('child_category.videos')->with(['child_category_count' => function ($q) {
            return $q->where('is_active', 1)->has('videos');
        }])->orderBy('is_leaf_category', 'desc')->get();
        $exams = Collection::where('is_active', 1)->get();
        return ['sub-categories' => $subcategory, 'exam' => $exams];
    }

    /**
     * Funtion to validate the input types
     *
     * @return object
     */
    public function validateVideoType()
    {
        $this->setRules(['type' => 'required|in:recent,related,trending', 'id' => 'required_if:type,related']);
        $this->_validate();
    }

    public function getMainCategory($is_live = 0)
    {

        return Category::where('parent_id', 0)->where('level', 0)->with('child_category')->has('child_category')->where('is_active', 1)->where('is_live', $is_live)->orderBy('category_order', 'asc')->paginate(15);
    }

    /**
     * Get all parent web series
     */
    public function parentWebseriesList()
    {
        $webcategories =  Category::where('parent_id', 0)->where('level', 0)->where('is_web_series', 1)->where('is_active', 1)->orderBy('category_order', 'asc')->paginate(10);
        return $webcategories;
    }

    /**
     * Get all category under the web series
     */
    public function getAllWebseries()
    {
        $webcategories =  Category::where('parent_id', 0)->where('level', 0)->where('is_web_series', 1)->where('is_active', 1)->orderBy('category_order', 'asc')->pluck('id');
        $weseriesCategoriesId = Category::whereIn('parent_id', $webcategories)->where('is_active', 1)->where('video_webseries_detail_id', '!=', null)->pluck('video_webseries_detail_id');
        $perPage = 20;
        $fields = 'video_webseries_detail.id,video_webseries_detail.title, video_webseries_detail.slug, video_webseries_detail.thumbnail_image,video_webseries_detail.poster_image, video_webseries_detail.created_at ';
        $weseriesCategories = Webseries::selectRaw($fields)->whereIn('id', $weseriesCategoriesId)->where('is_active', 1)->orderBy('title', 'asc')->paginate($perPage);
        $weseriesCategories = $weseriesCategories->toArray();
        $weseriesCategories['category_name'] = trans('general.webseries');
        $weseriesCategories['title'] = trans('general.webseries');
        return  $weseriesCategories;
    }

    /**
     *  Browse web series based on parent categories
     */
    public function browseChildWebseries($slug, $search, $perpage = 20)
    {

        $search  = !empty($search) ? str_replace("%20", ' ', $search) : '';
        $perpage = (int) $perpage;
        $parentCategory = Category::where($this->getKeySlugorId(), $slug)->where('is_active', 1)->first();
        $fields = 'video_webseries_detail.title, video_webseries_detail.slug, video_webseries_detail.thumbnail_image,video_webseries_detail.poster_image, video_webseries_detail.created_at ';
        if ($parentCategory) {
            $webseriesId =  Category::where('parent_id', $parentCategory->id)->orderBy('category_order', 'asc')->pluck('video_webseries_detail_id');
            $Categories =  Category::select('id', 'slug')->where('parent_id', $parentCategory->id)->orderBy('category_order', 'asc')->get();
            $episodes = array();
            foreach ($Categories as $category) {
                $no_of_episodes = DB::table('video_categories')->where('category_id', $category['id'])->count();
                $episodes[] = array('slug' => $category['slug'], 'episode_count' => $no_of_episodes);
            }

            if (!empty($search)) {
                $webseries = Webseries::selectRaw($fields)->whereIn('id', $webseriesId)->where('title', 'like', '%' . $search . '%')->where('is_active', 1)->with('genre')->orderBy('title', 'asc')->paginate($perpage);
            } else {
                $webseries = Webseries::selectRaw($fields)->whereIn('id', $webseriesId)->where('is_active', 1)->with('genre')->orderBy('title', 'asc')->paginate($perpage);
            }
            $webseries = $webseries->toArray();
            $webseries['episodes'] = $episodes;
            $webseries['category_name'] = 'All';//trans('general.webseries');
            $webseries['slug'] = 'web-series';
            return  $webseries;
        } else {
            if (!empty($search)) {
                $webseries = Webseries::selectRaw($fields)->where('id', null)->where('title', 'like', '%' . $search . '%')->orderBy('title', 'asc')->paginate($perpage);
            } else {
                $webseries = Webseries::selectRaw($fields)->where('id', null)->orderBy('title', 'asc')->paginate($perpage);
            }

            $webseries = $webseries->toArray();
            $webseries['category_name'] = 'All';//trans('general.webseries');
            $webseries['episodes'] = array();
            $webseries['slug'] = 'web-series';
            return  $webseries;
        }

        //$parentCategory = Category::where($this->getKeySlugorId(), $slug)->where('is_active', 1)->first();

        // if ($parentCategory) {
        //        $webseriesId =  Category::where('parent_id', $parentCategory->id)->orderBy('category_order', 'asc')->pluck('video_webseries_detail_id');
        //     if(!empty($search)){
        //          $webseries = Webseries::whereIn('id', $webseriesId)->where('title', 'like', '%' . $search . '%')->where('is_active', 1)->with('genre')->paginate(15);
        //     }else{
        //         $webseries = Webseries::whereIn('id', $webseriesId)->where('is_active', 1)->with('genre')->paginate(15);
        //     }
        //     $webseries = $webseries->toArray();
        //     $webseries['category_name'] = trans('general.webseries');
        //     return  $webseries;
        // } else {
        //     if(!empty($search)){
        //         $webseries = Webseries::where('id', null)->where('title', 'like', '%' . $search . '%')->paginate(15);
        //     }else{
        //         $webseries = Webseries::where('id', null)->paginate(15);
        //     }
        //     $webseries = $webseries->toArray();
        //     $webseries['category_name'] = trans('general.webseries');
        //     return  $webseries;
        // }
    }


    /**
     *  Browse web series based on parent categories ( For Mobile) -- New / Popular /All
     */
    public function browseChildWebseriesMobileNew($slug, $search, $perpage = 50)
    {
        $search  = !empty($search) ? str_replace("%20", ' ', $search) : '';
        $perpage = (int) $perpage;
        $parentCategory = Category::where($this->getKeySlugorId(), $slug)->where('is_active', 1)->first();
        $fields = 'video_webseries_detail.title, video_webseries_detail.slug, video_webseries_detail.thumbnail_image,video_webseries_detail.poster_image, video_webseries_detail.created_at ';
        if ($parentCategory) {
            $webseriesId =  Category::where('parent_id', $parentCategory->id)->orderBy('category_order', 'asc')->pluck('video_webseries_detail_id');
            $Categories =  Category::select('id', 'slug')->where('parent_id', $parentCategory->id)->orderBy('category_order', 'asc')->get();
            $episodes = array();
            foreach ($Categories as $category) {
                $no_of_episodes = DB::table('video_categories')->where('category_id', $category['id'])->count();
                $episodes[] = array('slug' => $category['slug'], 'episode_count' => $no_of_episodes);
            }

            
            if (!empty($search)) {
                $webseries = Webseries::selectRaw($fields)->whereIn('id', $webseriesId)->where('title', 'like', '%' . $search . '%')->where('is_active', 1)->with('genre')->orderBy('created_at', 'desc')->paginate($perpage);
            } else {
                //$webseries = Webseries::selectRaw($fields)->whereIn('id', $webseriesId)->where('is_active', 1)->with('genre')->whereDate('created_at', '>', Carbon::now()->subDays(30))->orderBy('created_at', 'desc')->paginate($perpage);
                $webseries = Webseries::selectRaw($fields)->whereIn('id', $webseriesId)->where('is_active', 1)->with('genre')->orderBy('created_at', 'desc')->paginate($perpage);
            }
            $webseries = $webseries->toArray();
            $webseries['episodes'] = $episodes;
            
        } else {
            if (!empty($search)) {
                $webseries = Webseries::selectRaw($fields)->where('id', null)->where('title', 'like', '%' . $search . '%')->orderBy('created_at', 'desc')->paginate($perpage);
            } else {
                //$webseries = Webseries::selectRaw($fields)->where('id', null)->whereDate('created_at', '>', Carbon::now()->subDays(30))->orderBy('created_at', 'desc')->paginate($perpage);
                $webseries = Webseries::selectRaw($fields)->where('id', null)->orderBy('created_at', 'desc')->paginate($perpage);
            }

            $webseries = $webseries->toArray();
            $webseries['episodes'] = array();
        }
        $webseries['category_name'] = "New on Series";
        $webseries['slug'] = 'web-series';
        return $webseries;
    }

    public function browseChildWebseriesMobilePopular($slug, $search, $perpage = 20)
    {

        //for popular 
        $fields = 'videos.id, videos.title, videos.slug, videos.thumbnail_image,videos.is_live, videos.view_count, is_webseries, c.slug as main_slug, vs.season_id';
       
        $myfinal = Video::selectRaw($fields)
            ->leftjoin('video_categories as vc1', 'vc1.video_id', '=', 'videos.id')
            ->leftjoin('categories as c','vc1.category_id', '=', 'c.id')
            ->leftjoin('video_seasons as vs', 'vs.video_id', '=', 'videos.id')
            ->where('videos.is_live', 0)->where('videos.is_active', 1)->where('videos.job_status', 'Complete')->where('videos.is_archived', 0)->where('videos.is_webseries', 1)->where('videos.is_adult', 0)->where('videos.view_count','>', 0);

        if (!empty($search)) {
            $myfinal->where('videos.title', 'like', '%' . $search . '%');
        }
        
        $result = $myfinal->orderBy('videos.view_count', 'desc');
        $result = $result->paginate($perpage);
        $result = $result->toArray();
        $result['category_name'] = "Popular in Series";
        $webseries['slug'] = 'web-series';
        return $result;
    }

    /**
     *  Browse web series detail
     */
    public function browseWebseries($slug)
    {
        $webseries = Webseries::where($this->getKeySlugorId(), $slug)->first();
        \Log::info($webseries->id);
        if ($webseries) {
            $webseries_details =  Category::with('webseriesDetail.genre', 'webseriesDetail.parent_category')->where('video_webseries_detail_id', $webseries->id)->orderBy('category_order', 'asc')->first();
            return $webseries_details;
        }
        return $webseries_details = null;
    }

    public function getVideoSeasons($webseries_details)
    {
        $category_id = Category::where('slug', $webseries_details->slug)->pluck('id');
        $videoCategory = DB::table('video_categories')->whereIn('category_id', $category_id)->get();
        if (count($videoCategory) > 0) {
            $fetchedVideos = Video::find($videoCategory[0]->video_id);
            $seasons = $this->getSeasons($fetchedVideos);
            return $seasons;
        } else {
            return $seasons = [];
        }
    }

    public function getVideoSeasonVideoSlug($fetchedVideos, $season_id)
    {
        $webseries_details = $this->getSeasonVideoSlug($fetchedVideos, $season_id, 'web-series');
        return $webseries_details;
    }
}
