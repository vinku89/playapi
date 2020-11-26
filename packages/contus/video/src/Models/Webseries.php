<?php

/**
 * Webseries Models.
 *
 * @name webseries
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCategory;
use Contus\Base\Contracts\AttachableModel;
use Symfony\Component\HttpFoundation\File\File;
use Contus\Video\Models\Video;
use Contus\Video\Models\Group;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Contus\Video\Models\CategoryTranslation;

use ScoutElastic\Searchable;
use Contus\Base\Elastic\Indices\WebseriesIndexConfigurator;
use Contus\Base\Elastic\Rules\WebseriesSearchRule;

class Webseries extends Model implements AttachableModel {

    use Searchable;

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'video_webseries_detail';

    /**
     * Set the elastic index name
     */
    protected $indexConfigurator = WebseriesIndexConfigurator::class;

    /**
     * Rules for the elasticearch to search records
     */
    protected $searchRules = [
            WebseriesSearchRule::class,
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
            'is_active' => [
                'type' => 'integer',
            ],
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'title',StringLiterals::ISACTIVE,'parent_category_id','description', 'genre_id', 'starring', 'webseries_order' ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = [ 'thumbnail_image', 'poster_image' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','webseries_order','is_active_home','updated_at','updator_id','creator_id'] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'title', 'slug' );
        $keysArray = array('category_listing_page','dashboard_categories','dashboard_exams','dashboard_categorynave');
        $this->clearCache($keysArray);
        Cache::forget ( 'relatedCategoryList' . $this->slug );
    }

    /**
     * HasOne relationship for category.
     */
    public function parent_category() {
        $retunParentCategory = $this->belongsTo ( Category::class, 'parent_category_id', 'id');
        return $retunParentCategory;
    }
    
    /**
     * HasOne relationship for category.
     */
    public function child_category() {
        $returnChildCategory = $this->hasMany ( Category::class, 'parent_id', 'id' );
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            $returnChildCategory = $returnChildCategory->where ( 'categories.is_active', 1 )->orderBy ( 'is_leaf_category', 'desc' );
        }
        return $returnChildCategory;
    }
   
    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     *
     * @package Category
     * @return Contus\Video\Models\Category
     */
    public function getFileModel() {
        return $this;
    }
    /**
    * Get the formated created date
    *
    * @return object  
    */
    public function getFormattedCreatedDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('M d Y');
    }

    /**
     * Method to get genre
     */
    public function genre() {
        $genre = $this->belongsTo ( Group::class, 'genre_id', 'id');
        return $genre;
    }

}
