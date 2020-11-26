<?php

/**
 * Subscription Scheduler
 *
 * @name SubscriptionScheduler
 * @vendor Contus
 * @package customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Contus\Customer\Models\Customer;
use Contus\Customer\Models\Subscribers;
use Contus\Notification\Repositories\NotificationRepository;

class SubscriptionScheduler extends Scheduler
{
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Function to set the frequency for the scheduler
     *
     * {@inheritDoc}
     * @see \Contus\Base\Schedulers\Scheduler::frequency()
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        $event->daily('00:01');
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call()
    {
        return function () {
            $user = Customer::has('Subscriber')->get();
            $type = 'subscription';
            foreach ($user as $userData) {
                $subscriber = Subscribers::where('customer_id', $userData->id)->where('is_active', 1)->orderBy('id', 'desc')->first();
                if (isset($subscriber->end_date) && $subscriber->end_date !== '0000-00-00') {
                    $endDay = new \DateTime($subscriber->end_date);
                } else {
                    break;
                }
                $now = new \DateTime(Carbon::today()->toDateString());
                $interval = $endDay->diff($now);
                $days = $interval->format("%r%a");
                switch ($days) {
                  case '2':
                    $notificationText = "Your subscription plan Expires in 3 days.! please subscribe to use continues unlimited services";
                    break;
                  case '1':
                    $notificationText = "Your subscription plan Expires in 2 days.! please subscribe to use continues unlimited services";
                    break;
                  case '0':
                    $notificationText = "Your subscription plan Expires in 1 days.! please subscribe to use continues unlimited services";
                    break;
                  case '-1':
                    $notificationText = "Your subscription plan Expired please subscribe to use continues unlimited services";
                    break;
                }
                if (!empty($notificationText)) {
                    $notify = new NotificationRepository();
                    $notify->addNotifications($userData, null, $type, $notificationText);
                    if (!empty($userData->device_token)) {
                        $fcmData = array("message" => $notificationText,"notification_type" => $type);
                        $notify->pushNotification($userData, $fcmData);
                    }
                }
            }
        };
    }
}
