<?php

/**
 * Notification Repository
 *
 * To manage the functionalities related to the Notification module from Notification Controller
 *
 * @name NotificationRepository
 * @vendor Contus
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Repositories;

use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Contus\Notification\Models\Notification;
use Contus\Customer\Models\Customer;
use Contus\Notification\Traits\NotificationTrait as Notifiy;
use Contus\Notification\Models\NotificationUser;
use Carbon\Carbon;
use Log;

class NotificationRepository extends BaseRepository
{
    use Notifiy;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_notification;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Notification
     * @param Contus\Notification\Models\Notification $notification
     */
    public function __construct()
    {
        parent::__construct();
        $this->_notification = new Notification();
       
        $this->_customer = new Customer();
    }

    /**
     * function to get the notification for user or customer
     * @vendor Contus
     *
     * @package Notification
     * @param int $notificationId
     * @return object
     */
    public function getUserNotifications()
    {
        return $this->_notification->with(['customer'  => function($query) {
            $query->withTrashed();
        }, 'video'])->where('user_id', authUser()->id)->orderBy('_id', 'desc')->paginate(config('access.perpage'))->toArray();
    }


    /**
     * Mark all message as read
     * @return json
     */
    public function markAsReadAll()
    {
        $response['success'] = false;
        try {
            $allNotification = $this->_notification->where('user_id', authUser()->id)->first();
            $allNotification->read_at = Carbon::now()->toDateTimeString();
            $allNotification->update();
            $this->clearCount();
            $response['message'] = trans('notification::notification.all_read_success');
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['message'] = trans('notification::notification.something_wrong');
        }
        return $response;
    }

    /**
     * Mark single message as read
     * @return json
     */
    public function markAsRead($id)
    {
        $response['success'] = false;
        try {
            $allNotification = $this->_notification->where('user_id', authUser()->id)->where('_id', $id)->first();
            $allNotification->read_at = Carbon::now()->toDateTimeString();
            $allNotification->update();
            $this->clearCount(true);
            $response['message'] = trans('notification::notification.all_read_success');
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['message'] = trans('notification::notification.something_wrong');
        }
        return $response;
    }

    /**
     * Remove all notifications
     * @return json
     */
    public function removeAll()
    {
        $response['success'] = false;
        try {
            $allNotification = $this->_notification->where('user_id', authUser()->id)->delete();
            $this->clearCount();
            $response['message'] = trans('notification::notification.all_remove_success');
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['message'] = trans('notification::notification.something_wrong');
        }
        return $response;
    }

    /**
     * Remove all notifications
     * @return json
     */
    public function remove($id)
    {
        $response['success'] = false;
        try {
            $allNotification = $this->_notification->where('user_id', authUser()->id)->where('_id', $id)->delete();
            $this->clearCount(true);
            $response['message'] = trans('notification::notification.all_remove_success');
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['message'] = trans('notification::notification.something_wrong');
        }
        return $response;
    }

    /**
     * Clear all the count
     * @return json
     */
    public function clearCount($single = false)
    {
        $response['success'] = false;
        try {
            $notificationClear = NotificationUser::where('user_id', authUser()->id);
            if (!empty($notify = $notificationClear->first()) && !$single) {
                $notify->count = 0;
                $notify->update();
            } elseif (!empty($notificationClear) && $single) {
                if($notificationClear->count > 0) {
                    $notificationClear->decrement('count');
                }
            }
            $response['message'] = trans('notification::notification.notification_clear');
            $response['success'] = true;
        } catch (\Exception $e) {
            $response['message'] = trans('notification::notification.something_wrong');
        }
        return $response;
    }

    /**
     * Get user notification count
     * @return integer
     */
    public function getNotificationCount($id)
    {
        $notificationUser = NotificationUser::where('user_id', $id)->first();
        return ($notificationUser) ? $notificationUser->count : 0;
    }
}
