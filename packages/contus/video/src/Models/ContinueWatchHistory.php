<?php

/**
 * Continue Watch History Model for continue_watch_history table in database
 *
 * @name ContinueWatchHistory
 * @vendor Contus -- vinod
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Contus\Video\Models\Countries;
use Illuminate\Support\Facades\Config;

class ContinueWatchHistory extends Model {
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'continue_watch_history';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'video_id','country_id','category_id','user_id','is_series','title', 'series_title','episode_name', 'duration' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','created_at','updated_at' ] );
    }

    /**
     * Belongsto relationship between video_categories and videos
     */
    public function video() {
        return $this->belongsTo ( Video::class, 'video_id' )->select ( 'id', 'title' );
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

