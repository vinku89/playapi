<?php

/**
 * Video Model for videos table in database
 *
 * @name Video
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\TranscodedVideo;
use Contus\Base\Contracts\AttachableModel;
use Symfony\Component\HttpFoundation\File\File;
use Contus\Video\Models\VideoCategory;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCountries;
use Contus\Video\Models\Countries;
use Contus\Video\Models\VideoPoster;
use Contus\Video\Models\Comment;
use Contus\video\Models\VideoRelation;
use Contus\Customer\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Contus\Video\Traits\VideoTrait;
use Contus\Video\Models\Group;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Contus\Base\Elastic\Indices\VideoIndexConfigurator;
use Contus\Base\Elastic\Rules\VideoSearchRule;
use ScoutElastic\Searchable;
use Contus\Video\Models\Tag;
use Contus\Video\Models\VideoSeason;
use Contus\Video\Models\Cast;
use Contus\Video\Models\VideoXrayCast;

class Video extends Model implements AttachableModel
{
    use VideoTrait,HybridRelations, Searchable;
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'videos';
    /**
     * Morph class name
     *
     * @var string
     */
    protected $morphClass = 'videos';
    protected $primaryKey = 'id';

    /**
     * Set the elastic index name
     */
    protected $indexConfigurator = VideoIndexConfigurator::class;

    /**
     * Rules for the elasticearch to search records
     */
    protected $searchRules = [
            VideoSearchRule::class,
        ];

    /**
     * Set Mapping for the genres type
     */
    protected $mapping = [
            'properties' => [
                'title' => [
                    'type' => 'text',
                    'analyzer' => 'search_analyzer'
                ],
                'category' => [
                    'type' => 'text',
                    'analyzer' => 'search_analyzer'
                ],
                'genre' => [
                    'type' => 'text',
                    'analyzer' => 'search_analyzer'
                ],
                'is_active' => [
                    'type' => 'integer',
                ],
                'is_adult' => [
                    'type' => 'long',
                ],
                'is_archived' => [
                  'type' => 'long',
                ],
                'job_status' => [
                  'type' => 'text',
                ],
                'tags' => [
                    'type' => 'text',
                    'analyzer' => 'search_analyzer',
                ],
                'presenter' => [
                    'type' => 'text',
                    'analyzer' => 'search_analyzer',
                ],
                "published_on" => [
                  'type' => 'date',
                  'format' => 'YYYY-MM-dd',
                 ],
                'created_at' => [
                  'type' => 'date',
                  'format' => 'YYYY-MM-dd HH:mm:ss',
                ]
            ]
        ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();
        $category = app()->request->category_ids;
        $genre = app()->request->exam_ids;
        if (!empty($genre)) {
            $array['genre'] = $this->genreName($genre[0]);
        } else {
            $array['genre'] = $this->getGenreNameAttribute();
        }
        if (!empty($category)) {
            $array['category'] = $this->categoryName($category[0]);
        } else {
            $array['category'] = $this->getCategoryNameAttribute();
        }
        $array['tags'] = $this->tagNames();
        if (!empty($array['published_on'])) {
            $array['published_on'] = Carbon::parse($array['published_on'])->toDateString();
        }
        return $array;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'category_id','title','description','is_featured','is_subscription','is_active','pdf','is_featured_time','published_on', 'video_category_slug', 'parent_category_slug'];

    /**
     * The attributes added from the model while fetching.
     *
     * @var array
     */
    //protected $appends = [ 'genre_name', 'video_category_name', 'is_subscribed'];
    protected $appends = ['video_category_name' ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = [ 'thumbnail_image', 'poster_image' ];

    protected $connection = 'mysql';
    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHiddenCustomer([ 'id','notification_status','aws_prefix','is_hls','video_url','pipeline_id','preview_image','subscription','job_id','country_id','is_subscription','is_featured','trailer','disclaimer','thumbnail_path','creator_id','updator_id','updated_at','archived_on','fine_uploader_uuid','fine_uploader_name','youtubePrivacy','liveStatus','pivot', 'short_description', 'broadcast_location', 'stream_id', 'source_url', 'encoder_type', 'hosted_page_url', 'username', 'password', 'stream_name' ]);
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving()
    {
        $this->setDynamicSlug('title');
        $this->saveImage('pdf');
        $this->saveImage('word');
        $keys = array('dashboard_categorynave','category_listing_page','dashboard_categories','dashboard_videos','category_live','category_tags','dashboard_live','dashboard_trending','dashboard_video_count','dashboard_pdf_count','dashboard_audio_count','is_live' );
        $this->clearCache($keys);
    }
    /**
     * HasMany relationship between videos and transcoded_videos
     */
    public function transcodedvideos()
    {
        return $this->hasMany(TranscodedVideo::class);
    }
    /**
     * Funtion to append the demo feature in video listing page and detail page
     *
     * @return boolean
     */
    public function getIsDemoAttribute()
    {
        if(!empty(authUser()->id)) {
            return authUser()->isExpires() ? 0 : 1;
        }
        else {
            return 1;
        }
    }

    /**
     * HasMany relationship between videos and video_categories
     */
    public function videocategory()
    {
        return $this->hasMany(VideoCategory::class);
    }

    /**
     * HasMany relationship between videos and video_countries
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'video_id');
    }

    /**
     * belongsToMany relationship between countries and countries_videos
     */
    public function countries()
    {
        return $this->belongsToMany(Countries::class, 'country_categories', StringLiterals::VIDEOID, 'country_id');
    }

    /**
     * belongsToMany relationship between collection and collections_videos
     */
    public function collections()
    {
        return $this->belongsToMany(Group::class, 'collections_videos', StringLiterals::VIDEOID, 'group_id')->withTimestamps();
    }

    /**
     * belongsToMany relationship between collection and collections_videos
     */
    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'video_playlists', StringLiterals::VIDEOID, 'playlist_id');
    }

    /**
     * belongsToMany relationship between tag and video_tag
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'video_tag', StringLiterals::VIDEOID, 'tag_id');
    }

    /**
     * belongsToMany relationship between categories and video_categories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'video_categories', StringLiterals::VIDEOID, 'category_id');
    }
    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function authfavourites()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->belongsToMany(Customer::class, 'favourite_videos')->where('customer_id', (auth()->user()) ? auth()->user()->id : 0)->selectRaw('IF(count(*)>0,count(*),0) as favourite  , favourite_videos.created_at as favourite_created_at')->groupBy('favourite_videos.video_id');
        } else {
            return $this->belongsToMany(Customer::class, 'favourite_videos');
        }
    }
    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     *
     * @package Base
     * @return Contus\Base\Model\Video
     */
    public function getFileModel()
    {
        return $this;
    }
    /**
     * Set the file to Staplaer
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @param string $config
     * @return void
     */
    public function setFile(File $file, $config)
    {
        if (isset($config->image_resolution)) {
            $this->thumbnail_image = url("$config->storage_path/" . $file->getFilename());
            $this->thumbnail_path = $file->getPathname();
        }
        if (isset($config->is_file)) {
            $this->mp3 = url("$config->storage_path/" . $file->getFilename());
            $this->subtitle_path = $file->getPathname();
        }

        return $this;
    }
    /**
     * Store the file information to database
     * if attachment model is already has record will update
     *
     * @param Contus\Video\Models\Video $video
     * @return boolean
     */
    public function upload(Video $video)
    {
        return $video->save();
    }

    /**
     * HasMany relationship between videos and Video_questions
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Set explicit model condition for fronend
     *
     * {@inheritdoc}
     *
     * @see \Contus\Base\Model::whereCustomer()
     *
     * @return object
     */
    public function whereCustomer()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->where('videos.is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->whereIn('is_subscription', ( (!empty(authUser()->id) && authUser()->isExpires()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]));
        } else {
            return $this->where('job_status', 'Complete')->where('is_archived', 0);
        }
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
    public function whereliveVideo()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('is_live', 1)->where('liveStatus', '!=', 'complete')->whereRaw('scheduledStartTime > "' . Carbon::now()->toDateString() . ' 00:00:00 "');
        }
    }
    /**
     * Get the scheduled as well as recorded live video lists
     */
    public function whereallliveVideo()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('is_live', 1)->where('liveStatus', '!=', 'complete')->whereRaw('scheduledStartTime > "' . Carbon::now()->toDateString() . ' 00:00:00 "');
        }
    }
    /**
     * This function used to get the recorded live videos
     */
    public function whereRecordedliveVideo()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('is_live', 1)->where('liveStatus', '!=', 'complete')->whereRaw('scheduledStartTime > "' . Carbon::now()->toDateString() . ' 00:00:00 "');
        }
    }
    /**
     * HasMany relationship between videos and video_posters
     */
    public function recent()
    {
        if (config()->get('auth.providers.users.table') === 'customers') {
            return $this->belongsTo(Customer::class)->where('customers.id', auth()->user()->id);
        } else {
            return $this->belongsToMany(Customer::class, 'recently_viewed_videos');
        }
    }
    public function season () {
        return $this->hasMany(VideoSeason::class, 'video_id', 'id');
    }

        /**
     * belongsToMany relationship between categories and video_seasons
     */
    public function castInfo()
    {
        return $this->belongsToMany(Cast::class, 'video_x_ray_cast','video_id','x_ray_cast_id')->where('x_ray_cast.is_active', 1);
    }
}
