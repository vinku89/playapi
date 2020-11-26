<?php

/**
 * MyAccount Controller
 *
 * To manage the Dashboard page view funtionalities
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Customer\Api\Controllers\Account;

use Contus\Base\Controller as BaseController;

use Contus\Customer\Repositories\CustomerRepository;
use Auth;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Session\TokenMismatchException;
use Contus\Video\Models\Playlist;
use Contus\Video\Models\FollowPlaylist;
use Contus\Video\Models\Collection;
use Contus\Customer\Models\Customer;
use Contus\Customer\Models\Subscribers;
use Carbon\Carbon;

class MyAccountController extends BaseController
{

    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $MyAccountRepository;
    public $uploadRepository;

    /**
     * Construct method
     */
    public function __construct(CustomerRepository $MyAccountRepository, UploadRepository $uploadRepository, Playlist $myplaylist)
    {
        parent::__construct();
        $this->_repository = $MyAccountRepository;
        $this->_repository->setRequestType(static::REQUEST_TYPE);
        $this->uploadRepository = $uploadRepository;
        $this->playlist = $myplaylist;
    }

    /**
     * To get the Subscription info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo()
    {
        return $this->getSuccessJsonResponse(['info' => ['rules' => $this->repository->getRules(), 'exams' => Collection::where('is_active', 1)->get()]]);
    }

    /**
     * Method to get profile information
     *
     * @return to $stateProvider state
     */
    public function profileData()
    {
        $checkSubscriber = Subscribers::where('customer_id', authUser()->id)->where('is_active', 1)->first();
        $data ['subscription'] = SubscriptionPlan::get();
        if (!empty($checkSubscriber) && count($checkSubscriber->toArray()) > 0) {
            $data ['subscription_plan'] = SubscriptionPlan::where('id', $checkSubscriber->subscription_plan_id)->select('name', 'slug')->first();
        }
        $data ['subscribed_plan'] = authUser()->activeSubscriber()->first();
        $data ['plan_duration_left'] = '';
        if ($data ['subscribed_plan']) {
            $end = Carbon::parse($data ['subscribed_plan']->pivot->end_date);
            $now = Carbon::now();
            $length = $end->diffInDays($now);
            $data ['plan_duration_left'] = $length . ' days left';
        }

        $customerInfo = Customer::selectRaw('id, name, email, phone, dob, age, profile_picture, is_locked, notify_email, country_code, iso, id as favourite_count, id as playlist_count, id liked_count, id notification_count, id watch_count')->where('id', authUser()->id)->first();

        $data['profile'] = $customerInfo->makeHidden('expires_at');
        return ($data) ? $this->getSuccessJsonResponse(['response' => $data]) : $this->getErrorJsonResponse(trans('customer::customer.showError'));
    }

    /***
     * Get the profile image for the auth user
     * @return \Contus\Base\response
     */
    public function postProfileImage()
    {
        $tempImage = $this->uploadRepository->tempUploadImage();
        return empty ($tempImage) ? $this->getErrorJsonResponse([], trans('customer::user.account.unable_to_upload')) : $tempImage;

    }

    /**
     * function to update Customer Notification Status
     *
     * @param Request $request
     * @param int $customerId
     * @return \Contus\Base\response
     */
    public function updateNotificationStatus()
    {
        $update = $this->_repository->UpdateCustomerNotificationStatus();
        return (isset($update)) ? $this->getSuccessJsonResponse(['message' => trans('customer::customer.updatedNotifications')]) : $this->getErrorJsonResponse([], trans('customer::customer.updatedError'));
    }

}
