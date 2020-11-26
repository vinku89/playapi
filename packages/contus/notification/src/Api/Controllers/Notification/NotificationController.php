<?php

/**
 * Notification Repository
 *
 * To manage the functionalities related to the Notification api methods
 *
 * @name NotificationController
 * @vendor Contus
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Api\Controllers\Notification;

use Contus\Base\ApiController;
use Contus\Notification\Repositories\NotificationRepository;

class NotificationController extends ApiController
{
    /**
     * Construct method
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        parent::__construct();
        $this->repository = $notificationRepository;
        $this->repository->setRequestType(static::REQUEST_TYPE);

        $this->repoArray = ['repository'];
    }
    /**
     * function to get the notification
     *
     * @return \Contus\Base\response
     */
    public function getNotifications()
    {
        $data = $this->repository->getUserNotifications();
        return ($data) ? $this->getSuccessJsonResponse([ 'response' => $data,'message' => trans('notification::notification.success') ]) : $this->getErrorJsonResponse([ 'data' => $data ], trans('notification::notification.showError'));
    }
    /**
     * Function to trigger notification async through curl
     */
    public function setNotification()
    {
        if (config()->get('auth.providers.users.table') !== 'customers' && ! (auth()->user())) {
            $id = $this->request->header('x-user-id');
            auth()->loginUsingId($id);
        }
        $this->repository->setNotify();
    }

    /**
     * Update notiication settings
     * @return json
     */
    public function updateSettings()
    {
        $notification = $this->repository->notificationSettings();
        if ($notification['success']) {
            return $this->getSuccessJsonResponse(['response' => $notification['data'], 'message' => $notification['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $notification['message']]);
    }

    /**
     * Mark all message as read
     * @return json
     */
    public function markAsReadAll()
    {
        $markAllNotification = $this->repository->markAsReadAll();
        if ($markAllNotification['success']) {
            return $this->getSuccessJsonResponse(['message' => $markAllNotification['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $markAllNotification['message']]);
    }

    /**
     * Mark single message as read
     * @return json
     */
    public function markAsRead($id)
    {
        $markNotification = $this->repository->markAsRead($id);
        if ($markNotification['success']) {
            return $this->getSuccessJsonResponse(['message' => $markNotification['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $markNotification['message']]);
    }

    /**
     * Remove all notifications
     * @return json
     */
    public function removeAll()
    {
        $removeAllNotification = $this->repository->removeAll();
        if ($removeAllNotification['success']) {
            return $this->getSuccessJsonResponse(['message' => $removeAllNotification['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $removeAllNotification['message']]);
    }

    /**
     * Remove single notifications
     * @return json
     */
    public function remove($id)
    {
        $removeNotification = $this->repository->remove($id);
        if ($removeNotification['success']) {
            return $this->getSuccessJsonResponse(['message' => $removeNotification['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $removeNotification['message']]);
    }

    /**
     * Clear all the count
     * @return json
     */
    public function clearCount()
    {
        $clearNotification = $this->repository->clearCount();
        if ($clearNotification['success']) {
            return $this->getSuccessJsonResponse(['message' => $clearNotification['message']]);
        }
        return $this->getErrorJsonResponse(['message' => $clearNotification['message']]);
    }
}
