<?php

/**
 * Watch History Models.
 *
 * @name WatchHistory
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Video\Models\Video;
use Contus\User\Models\User;
use Contus\Customer\Models\Customer;
use Carbon\Carbon;
use Contus\Base\MongoModel ;

class WatchHistory extends MongoModel
{
    protected $primaryKey = '_id';
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $collection = 'watch_history';
    protected $connection = 'mongodb';
    protected $appends = [];
    /**
     * Hidden variable to be returned
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $hidden = [ ];
    
    public function bootSaving()
    {
        $keys = array('watch_history');
        $this->clearCache($keys);
    }
}
