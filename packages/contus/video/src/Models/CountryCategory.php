<?php

/**
 * Country Category Model for country_categories table in database
 *
 * @name CountryCategory
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2019 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Illuminate\Support\Facades\Config;

class CountryCategory extends Model {
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'country_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'video_id','category_id' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','video_id','category_id','created_at','updated_at' ] );
    }

    /**
     * Belongsto relationship between video_categories and videos
     */
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'country_categories', 'country_id', 'video_id');
    }

    /**
     * Belongsto relationship between country_categories and Country
     */
    public function country() {
        return $this->belongsTo ( Countries::class, 'country_id' );
    }

    /**
     * Belongsto relationship between video_categories and categories
     */
    public function category() {
        return $this->belongsTo ( Category::class, 'category_id' );
    }
}

