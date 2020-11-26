<?php
/**
 * Countries Models.
 *
 * @name       Countries
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\VideoCountries;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;

class Countries extends Model {

  /**
   * The database table used by the model.
   *
   * @vendor     Contus
   * @package    Video
   * @var string
   */
  protected $table = 'countries';

  /**
   * The attributes that are mass assignable.
   *
   * @vendor     Contus
   * @package    Video
   * @var array
   */
  protected $fillable = [
      'code',
      'name',
  ];

  /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
      parent::__construct ();
      $this->setHiddenCustomer ( ['is_active','updated_at','created_at','updator_id','creator_id' ] );
  }

  /**
   * HasMany relationship between countries and video_countries
   */
  public function videocountry() {
      return $this->hasMany ( VideoCountries::class );
  }
  /**
     * belongsToMany relationship between categories and video_categories
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'country_categories', 'country_id', 'category_id');
    }
    /**
     * belongsToMany relationship between categories and video_categories
     */
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'country_categories', 'country_id', 'video_id');
    }
}