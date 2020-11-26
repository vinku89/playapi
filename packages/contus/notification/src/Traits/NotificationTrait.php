<?php

/**
 * NotificationTrait
 *
 * To manage the functionalities for send the notification.
 *
 * @vendor Contus
 *
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 *
 */
namespace Contus\Notification\Traits;

use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Cms\Models\EmailTemplates;
use Contus\Video\Models\Comment;
use Contus\User\Models\User;
use Contus\Notification\Models\Notification;
use Contus\Customer\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Contus\Video\Models\Question;
use Contus\Video\Models\Answer;
use Contus\Video\Models\Video;
use Contus\Notification\Models\NotificationUser;
use Log;

trait NotificationTrait
{
    /**
     * Function to add notification
     *
     *
     * @param string $type
     * @param int $typeId
     */
    public function notify($type, $typeId)
    {
        $curl = curl_init();
        if (config()->get('auth.providers.users.table') === 'customers') {
            $headers [] = 'X-REQUEST-TYPE: mobile';
            $headers [] = 'Authorization: Bearer ' . auth()->user()->access_token;
            $post = [ 'type' => $type,'type_id' => $typeId ];
            curl_setopt($curl, CURLOPT_URL, url('api/v2/notify'));
        } else {
            $headers [] = 'X-REQUEST-TYPE:mobile';
            $headers [] = 'Authorization: Bearer ' . auth()->user()->access_token;
            $post = [ 'type' => $type,'type_id' => $typeId ];
            curl_setopt($curl, CURLOPT_URL, url('api/admin/notify'));
        }
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, 'api');
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_POSTREDIR, 3);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curl);
    }
    /**
     * Function to set notification
     *
     * @param string $type
     * @param int $typeId
     */
    public function setNotifys($type, $typeId)
    {
        switch ($type) {
            case 'reply_comment':
                $this->replyComment($typeId, $type);
                break;
            case 'new_video':
                $this->newVideo($typeId, $type);
                break;
            default:
                break;
        }
    }
    /**
     * function to trigger notifcation via curl api
     */
    public function setNotify()
    {
        $this->setNotifys($this->request->type, $this->request->type_id);
    }

    /**
     * function to Send Email
     *
     * @param object $toUserDetail
     * @param string $subject
     * @param string $content
     */
    public function email($toUserDetail, $subject, $content)
    {
        try {
            Mail::send('base::layouts.email', [ 'content' => $content ], function ($message) use ($subject, $toUserDetail) {
                $message->from(env('MAIL_SENDER_ADDRESS'), config()->get('settings.general-settings.site-settings.site_name'));
               
                if (!is_object($toUserDetail)) {

                    $message->to($toUserDetail['email'], $toUserDetail['name'])->subject($subject);
                   
                }
                else{
                    $message->to($toUserDetail->email, $toUserDetail->name)->subject($subject);
                }
            
                     });
        } catch (\Exception $e) {
            app('log')->info('Email is not working with configured mail id ' . env('MAIL_USERNAME'));
        }
    }
    /**
     * function to select data to send email notification
     *
     * @param object $customers
     * @param object $users
     * @param object $email
     */
    public function sendEmailNotification($customers, $users, $email)
    {
        foreach ($customers as $customer) {
            $getcolumn = new Customer();
            $getcolumn = array_map(function ($str) {
                return '##' . strtoupper($str) . '##';
            }, $getcolumn->getTableColumns());
            $email->content = str_replace($getcolumn, $customer->toArray(), $email->content);
            $this->email($customer, $email->subject, $email->content);
        }
        foreach ($users as $customer) {
            $getcolumn = new User();
            $getcolumn = array_map(function ($str) {
                return '##' . strtoupper($str) . '##';
            }, $getcolumn->getTableColumns());
            $email->content = str_replace($getcolumn, $customer->toArray(), $email->content);
            $this->email($customer, $email->subject, $email->content);
        }
    }
    /**
     * function to add notification
     *
     * @param object $customer
     * @param object $users
     * @param string $type
     * @param int $type_id
     * @param string $content
     * @param string $type_type
   * @param object $curUser
     */
    public function addNotifications($user, $typeData = null, $type, $content, $sender_id = null)
    {
        $notification = new Notification();
        $notification->content = $content;
        $notification->video_id = ($typeData) ? $typeData->id : null;
        $notification->user_id = $user->id;
        $notification->type = $type;
        $notification->read_at = null;
        $notification->sender_id = $sender_id;
        if ($notification->save()) {
            $this->addCount($user->id);
        }
        return true;
    }

    /**
     * function to add notification for reply of comment
     *
     * @param int $typeId
     */
    private function replyComment($typeId, $type)
    {
        $comment = Comment::with('customer')->where('_id', $typeId)->first();
        if (!empty($comment)) {
            $video = $comment->video()->first();
            if ($comment->customer_id) {
                $customer = $comment->customer;
                $notificationCustomers = $comment->customer->notificationUser()->first();
                /**
                 * Add Notification
                 */
                if ((empty($notificationCustomers) || $notificationCustomers->reply_comment) && $this->authUser->id !== $customer->id) {
                    $notificationText = $this->authUser->name . ' has replied to your comment on video ' . $video->title;
                    $this->addNotifications($customer, $video, $type, $notificationText, $this->authUser->id);
                    if (!empty($comment->customer->device_token)) {
                        $fcmData = array("message" => $notificationText,"notification_type" => $type,'video_id' => $video->id );
                        $this->pushNotification($comment->customer, $fcmData);
                    }
                }
            }
        }
    }

    /**
     * function to add notification for new videos
     *
     * @param int $typeId
     */
    private function newVideo($typeId, $type)
    {
        $video = Video::find($typeId);
        $notificationCustomers = Customer::with('notificationUser')->get();
        /**
         * Add Notification
         */
        foreach ($notificationCustomers as $customer) {
            if ((empty($customer->notificationUser) || $customer->notificationUser->new_video) && $this->authUser->id !== $customer->id) {
                $notificationText = $video->title . ' video has been addded';
                $this->addNotifications($customer, $video, $type, $notificationText);
                if (!empty($customer->device_token)) {
                    $fcmData = array("message" => $notificationText,"notification_type" => $type,'video_id' => $video->id );
                    $this->pushNotification($customer, $fcmData);
                }
            }
        }
    }

    /**
     * Push notification for both android and ios
     *
     * @param object $customers
     * @param string $type
     * @param int $typeId
     * @param string $notificationText
     */
    public function pushNotification($customers, $fcmData)
    {
        $device_tokens = [];
        array_push($device_tokens, $customers->device_token);
        if ($customers->device_type == 'Android') {
            $format = [
              'registration_ids'=> $device_tokens,
              'priority' => "high",
              'data'=> $fcmData
          ];
        } elseif ($customers->device_type == 'IOS') {
            $notify = ['body'=> $fcmData['message'],'badge'=>1,'sound'=>'ping.aiff'];
            $format = [
                      'to'=> $customers->device_token,
                      'notification'=>$notify,
                      'priority' => "high",
                      'data'=> $fcmData
                  ];
        }
        if (!empty($format)) {
            $this->fcmPushNotification($format);
        }
    }

    /**
     * Function for FCM Push Notification
     *
     * @param string|array $reg_id
     * @param array $data
     */
    public function fcmPushNotification($fields)
    {
        try {
            $googleApiKey = env('FCM_KEY');
            $googleGcmUrl = 'https://fcm.googleapis.com/fcm/send';
            $headers = array($googleGcmUrl,'Content-Type: application/json','Authorization: key=' . $googleApiKey );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $googleGcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (\ErrorException $e) {
            Log::error($e);
        }
    }

    public function notificationSettings()
    {
        $response['success'] = false;
        try {
            $notificationUser = NotificationUser::where('user_id', authUser()->id)->first();
            if (!empty($notificationUser)) {
                $notificationUser->fill($this->request->all());
                $notificationUser->user_id = authUser()->id;
                $notificationUser->update();
            } else {
                $notificationUser = new NotificationUser();
                $notificationUser->new_video = 1;
                $notificationUser->reply_comment = 1;
                $notificationUser->auto_play = 1;
                $notificationUser->user_id = authUser()->id;
                $notificationUser->save();
            }
            $response['data']['notification_setting'] = NotificationUser::select('new_video', 'reply_comment', 'auto_play')->where('user_id', authUser()->id)->first();
            $response['success'] = true;
            $response['message'] = trans('notification::notification.success_notification_settings');
        } catch (\Exception $e) {
            $response['message'] = trans('notification::notification.error_notification_settings');
        }
        return $response;
    }

    public function addCount($user_id)
    {
        $notification = NotificationUser::where('user_id', $user_id);
        if ($notification->exists()) {
            $notification->increment('count');
        } else {
            $notificationUser = new NotificationUser();
            $notificationUser->new_video = 1;
            $notificationUser->reply_comment = 1;
            $notificationUser->user_id = $user_id;
            $notificationUser->count = 1;
            $notificationUser->save();
        }
        return true;
    }
}
