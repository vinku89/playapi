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

use Illuminate\Database\Eloquent\Model;

class GeoRegions extends Model {

  /**
   * The database table used by the model.
   *
   * @vendor     Contus
   * @package    Video
   * @var string
   */
  protected $table = 'geo_regions';

  /**
   * The attributes that are mass assignable.
   *
   * @vendor     Contus
   * @package    Video
   * @var array
   */
  protected $fillable = [
      'region_name',
      'short_code',
      'country_code',
      'is_active',
  ];
  
}