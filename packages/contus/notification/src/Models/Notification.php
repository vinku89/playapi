<?php

/**
 * Notification Model is used to manage the notifications in database
 *
 * @name Notification
 * @vendor Contus
 * @package notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\Video;

class Notification extends Eloquent
{
    protected $primaryKey = '_id';
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package notification
     * @var string
     */
    protected $collection = 'notifications';
    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package notification
     * @var array
     */
    protected $fillable = ['content'];

    /**
     * The attributes added from the model while fetching.
     *
     * @var array
     */
    protected $appends = [ 'date'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'user_id'];
    protected $dates = ['created_at', 'updated_at'];
    /**
     * Constructor method
     * sets hidden for notifications
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'sender_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
