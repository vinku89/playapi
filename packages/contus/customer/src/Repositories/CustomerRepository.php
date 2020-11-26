<?php

/**
 * Customer Repository
 *
 * To manage the functionalities related to the Customer module from Customer Controller
 *
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Customer\Repositories;

use Contus\Base\Repository as BaseRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Video\Models\Collection;
use Contus\Customer\Traits\CustomerTrait as CustomerTrait;
use Contus\Video\Repositories\PlaylistRepository;
use Contus\Video\Models\Playlist;
use Contus\Customer\Models\MypreferencesVideo;
use Contus\Video\Models\Category;
use Carbon\Carbon;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Customer\Models\Subscribers;
use Contus\Payment\Models\PaymentTransactions;

use JWTAuth;
use JWTAuthException;

class CustomerRepository extends BaseRepository
{
    use CustomerTrait;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_customer;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Customer
     * @param Contus\Customer\Models\Customer $customer
     */
    protected $notificationRepository;
    public function __construct(Customer $customer, EmailTemplatesRepository $emailTemplates, NotificationRepository $notificationRepository)
    {
        parent::__construct();
        $this->_customer = $customer;
        $this->email = $emailTemplates;
        $this->notificationRepository = $notificationRepository;
        $this->notification = new NotificationRepository();
        if (config()->get('auth.providers.users.table') === 'customers') {
            $this->setRules([ 'name' => 'required|max:100|min:3',StringLiterals::EMAIL => 'required|max:100|email|unique:customers,email,NULL,id,deleted_at,NULL','phone' => 'sometimes|numeric|min:6','acesstype' => StringLiterals::REQUIRED,StringLiterals::PASSWORD => 'required|min:6',//'password_confirmation' => 'required|same:password|min:6' 
            ]);
        } else {
            $this->setRules([ 'start_date' => 'sometimes|required','subscription_plan' => 'sometimes|required','orderid' => 'sometimes|required|numeric','is_active' => 'required|boolean','name' => 'required|max:100',StringLiterals::EMAIL => 'required|max:100|email|unique:customers,email,NULL,id,deleted_at,NULL','phone' => 'sometimes|numeric|min:6','age' => 'required','acesstype' => StringLiterals::REQUIRED,StringLiterals::PASSWORD => 'required|min:6',//'password_confirmation' => 'required|same:password|min:6' 
            ]);
        }
        $this->siteName = config ()->get ( 'settings.general-settings.site-settings.site_name' );
    }
    /**
     * Store a newly created customer or update the customer.
     *
     * @vendor Contus
     *
     * @package Customer
     * @param $id input
     * @return boolean|\Contus\Customer\Models\Customer
     */
    public function addOrUpdateCustomers($id = null)
    {
        $result['error'] = false;
        $result['message'] = '';
        $existingUser = '';
        if (! empty($id)) {
            $customer = $this->_customer->find($id);
            $existingUser = $customer->id;
            if (! is_object($customer)) {
                return false;
            }

            $this->setRule('email', 'required|email|unique:customers,email,'.$customer->id);
            $this->setRule('phone', 'sometimes:required|numeric|min:6');
            $this->setRule('age', 'sometimes|required');
            $this->setRule('acesstype', 'sometimes|required');
            $this->setRule('is_active', 'sometimes|required|boolean');
            $this->setRule('password', 'sometimes|required|min:6');
            //$this->setRule('password_confirmation', 'sometimes|required|same:password|min:6');

            $this->_validate();
            $customer->updator_id = $this->authUser->id;
            if ($this->request->has('age') && $this->request->age) {
                $customer->dob = $this->request->age;
            }
            if ($this->request->has('password') && $this->request->password) {
                $customer->password = Hash::make($this->request->password);
            }
        } else {
            $this->_validate();
            $softDeletedCustomers = $this->findSoftDeletedUser()->where('email', $this->request->email)->first();
            if (is_object($softDeletedCustomers) && $softDeletedCustomers->email === $this->request->email) {
                $customer = $softDeletedCustomers;
            } else {
                $customer = new Customer();
            }
            $customer->is_active = 1;
            $customer->creator_id = (isset($this->authUser->id)) ? $this->authUser->id : 0;
            if ($this->request->has('age') && $this->request->age) {
                $customer->dob = $this->request->age;
            }
            $customer->access_token = $this->randomCharGen(30);
            $customer->password = Hash::make($this->request->password);
        }

        $customer->fill($this->request->except('_token'));
        if (isset($softDeletedCustomers) && is_object($softDeletedCustomers) && $softDeletedCustomers->email === $this->request->email) {
            $customer->restore();
        }
        $customer->save();
        // BEGIN : Block to generate JWT token
        if ($id == null && !isAdmin()) {
            $token = JWTAuth::fromUser($customer);
            $customer->access_token = $token;
            $customer->update();
        }
        // END : Block to generate JWT token

        if ($existingUser === '') {
            // array_combine_function
            $preference = new PlaylistRepository(new Playlist(), new MypreferencesVideo());
            $category = Category::where('level', 1)->whereNotNull('preference_order')->where('is_active', 1)->orderBy('preference_order')->pluck('id')->toArray();
            $catType = [ ];
            foreach ($category as $k => $v) {
                $catType [$k] = 'sub-categories';
            }
            $this->email = $this->email->fetchEmailTemplate('new-customer-account-creation');
            $this->email->subject = str_replace(['##SITE_NAME##'], [$this->siteName], $this->email->subject);
            $this->email->content = str_replace(['##SITE_NAME##','##GREETING_NAME##','##EMAIL##', '##PASSWORD##','##URL##'], [$this->siteName,ucfirst($customer->name),$this->request->email, $this->request->password, env('FRONTEND_DOMAIN_URL')],$this->email->content);
            $this->notification->email($customer, $this->email->subject, $this->email->content);
        } else {
            $modifierName = (isAdmin())?'Administrator':Auth::user()->name;
            $this->email = $this->email->fetchEmailTemplate('customer-admin-account-update');
            $this->email->subject = str_replace(['##SITE_NAME##'], [$this->siteName], $this->email->subject);
            $this->email->content = str_replace(['##GREETING_NAME##','##DATE##','##MODIFIER_NAME##' ], [ $customer->name,'\''.date('d-m-Y h:i:s').'\'',$modifierName], $this->email->content);
            $this->notification->email($customer, $this->email->subject, $this->email->content);
        }

        $customer       = $this->_customer->SelectRaw('*, id as is_subscribed')->find($customer->id);
        $result['data'] = $customer->toArray();
        $result['data']['access_token'] = $customer->access_token;
        $result['data']['subscribed_plan'] = $customer->activeSubscriber()->first();
        $result['data']['notification_count'] = $this->notificationRepository->getNotificationCount($customer->id);
        return $result;
    }
    /**
     * This function used for Social registration
     *
     * @return number|\Contus\Customer\Models\Customer
     */
    public function socialRegister($id = null)
    {
        $this->setRules([ 'acesstype' => 'required','login_type' => 'required|in:normal,fb,google+','email' => 'required|max:100|email','name' => 'required','password' => 'required|confirmed|min:6', 'social_user_id' => 'required' ]);
        $this->_validate();
        $type = ($this->request->login_type == 'fb') ? 'facebook' : (($this->request->login_type == 'google+') ? 'google' : '');
        return $this->registerSocialUser($this->request->all(), $type);
    }

    /**
     * This function used to save the new password for the particular user
     *
     * @param string $random
     * @return boolean
     */
    public function savenewPassword($random)
    {
        $this->setRules([ 'password' => 'required|min:6',//'password_confirmation' => 'required|same:password|min:6' 
        ]);
        $this->_validate();
        $checkuser = $this->_customer->where('forgot_password', $random)->first();
        if (count($checkuser) > 0) {
            $checkuser->password = Hash::make($this->request->password);
            $checkuser->forgot_password = null;
            $checkuser->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function used to check the logout information for IOS device token make empty
     *
     * @return number|boolean
     */
    public function checkIOSCustomers(){
        $user = Customer::where('id', $this->authUser->id)->first();
        if (!empty($user) && count($user->toArray()) > 0) {
            $user->device_token = '';
            $user->acesstype = "mobile";
            $user->device_type = $this->request->device_type;
            return ($user->save()) ? $user->makeHidden([ 'id','access_token' ]) : 0;
        }else{
            return false;
        }
    }

    /**
     * This function used for the social login Customers
     *
     * @return number|\Contus\Customer\Models\Customer
     */
    public function checksocialCustomers()
    {
        $this->setRules([ 'login_type' => 'required|in:normal,fb,google+','email' => 'required|max:100|email','token' => 'required','social_user_id' => 'required','name' => 'required' ]);
        $this->_validate();

        $type = ($this->request->login_type == 'fb') ? 'facebook' : (($this->request->login_type == 'google+') ? 'google' : '');
        return $this->registerSocialUser($this->request->all(), $type);
    }

    /**
     * This function used for Update the notification Status based on on/off status
     *
     * @return number|\Contus\Customer\Models\Customer
     */
    public function UpdateCustomerNotificationStatus()
    {
        if (isset($this->request->type) && ($this->request->type == "on")) {
            $customer_id = $this->authUser->id;
            $notificationStatus = $this->_customer->where('id', $customer_id)->update([ 'notification_status' => 1 ]);
        } elseif (isset($this->request->type) && ($this->request->type == "off")) {
            $customer_id = $this->authUser->id;
            $notificationStatus = $this->_customer->where('id', $customer_id)->update([ 'notification_status' => 0 ]);
        }
        return $notificationStatus;
    }

    /**
     * Function to generate random number for OTP
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean
     */
    public function generateResetPassword()
    {
        $this->setRules([ StringLiterals::EMAIL => 'required|max:100|email' ]);
        $this->_validate();
        $this->_customer = $this->_customer->where('email', $this->request->email)->first();
        if (isset($this->_customer) && is_object($this->_customer) && ! empty($this->_customer->id)) {
            $this->_customer->access_otp_token = mt_rand();
            $this->_customer->save();
            $this->email = $this->email->fetchEmailTemplate('password_reset_otp');
            $this->email->content = str_replace([ '##USERNAME##','##OTP##' ], [ $this->_customer->name,$this->_customer->access_otp_token ], $this->email->content);
            $this->notification->email($this->_customer, $this->email->subject, $this->email->content);
            return true;
        }
        return false;
    }

    /**
     * Function to Check the OTP generated and reset password
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean
     */
    public function otpResetPassword()
    {
        $this->setRules([ StringLiterals::EMAIL => 'required|max:100|email','access_otp_token' => 'required|numeric','acesstype' => StringLiterals::REQUIRED,StringLiterals::PASSWORD => 'required|min:6',
        //'password_confirmation' => 'required|same:password|min:6' 
        ]);
        $this->_validate();
        $this->_customer = $this->_customer->where([ 'email' => $this->request->email,'access_otp_token' => $this->request->access_otp_token ])->first();
        if (isset($this->_customer) && is_object($this->_customer) && ! empty($this->_customer->id)) {
            $this->_customer->access_token = $this->randomCharGen(30);
            $this->_customer->access_otp_token = '';
            $this->_customer->acesstype = $this->request->acesstype;
            $this->_customer->password = Hash::make($this->request->password);
            if ($this->_customer->save()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Function to check and reset the password details
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean
     */
    public function resetPassword()
    {
        $result['error'] = false;
        $result['message'] = '';
        $this->setRules([ 'token' => 'required', StringLiterals::PASSWORD => 'required|min:6',//'password_confirmation' => 'required|same:password|min:6' 
        ]);
        $this->_validate();
        $customerInfo = $this->_customer->where('forgot_password', $this->request->token)->first();
        if (!empty($customerInfo)) {
            $customerInfo->forgot_password = null;
            $customerInfo->password = Hash::make($this->request->password);
            $customerInfo->save();
        } else {
            $result['error'] = true;
            $result['message'] = trans('passwords.token');
        }
        return $result;
    }

    /**
     * Function to check and reset the password details
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean
     */
    public function verifyResetPassword()
    {
        $result['error'] = false;
        $result['message'] = '';
        $this->setRules([ 'token' => 'required']);
        $this->_validate();
        $customerInfo = $this->_customer->where('forgot_password', $this->request->token)->first();
        if (empty($customerInfo)) {
            $result['error'] = true;
            $result['message'] = trans('passwords.token_expired');
        }
        return $result;
    }



    /**
     * Function to register Social User
     *
     * @param array $userDetails
     * @param string $type
     * @return number|\Contus\Customer\Models\Customer
     */
    public function registerSocialUser($userDetails, $type)
    {
        $result['error'] = false;
        $result['message'] = '';
        if (! filter_var($userDetails ['email'], FILTER_VALIDATE_EMAIL)) {
            return 0;
        }
        $softDeletedCustomers = $this->findSoftDeletedUser()->where('email', $userDetails ['email'])->where($type . '_user_id', $userDetails ['social_user_id'])->first();
        if (is_object($softDeletedCustomers) && $softDeletedCustomers->email === $userDetails ['email']) {
            $user = $softDeletedCustomers;
            $user->access_token = $this->randomCharGen(30);
            $user->restore();
        } else {
            $user = $this->_customer->where($type . '_user_id', $userDetails ['social_user_id'])->first();
        }
        if (! $user) {
            $normalUser = $this->_customer->where('email', $userDetails ['email'])->first();
            if ($normalUser) {
                if ($type == 'google') {
                    $normalUser->google_user_id = $userDetails ['social_user_id'];
                    $normalUser->google_auth_id = $userDetails ['token'];
                }
                if ($type == 'facebook') {
                    $normalUser->facebook_user_id = $userDetails ['social_user_id'];
                    $normalUser->facebook_auth_id = $userDetails ['token'];
                }
                $normalUser->access_token = $this->randomCharGen(30);
                $normalUser->save();
                $user = $normalUser;
            } else {
                $user = $this->_customer;
                if ($type == 'google') {
                    $user->google_user_id = $userDetails ['social_user_id'];
                    $user->google_auth_id = $userDetails ['token'];
                }
                if ($type == 'facebook') {
                    $user->facebook_user_id = $userDetails ['social_user_id'];
                    $user->facebook_auth_id = $userDetails ['token'];
                }

                $user->name = (!empty($user->name)) ? $user->name : $userDetails ['name'];
                $user->email = $userDetails ['email'];
                $user->access_token = $this->randomCharGen(30);
                $user->login_type = $userDetails ['login_type'];
                $user->is_active = 1;
                if (isset($userDetails ['password'])) {
                    $user->password = Hash::make($userDetails ['password']);
                }
                $user->profile_picture = (!empty($user->profile_picture)) ? $user->profile_picture : isset($userDetails ['profile_picture']) ? $userDetails ['profile_picture'] : '';

                if (isset($userDetails ['device_type'])) {
                    $user->device_type = $userDetails ['device_type'];
                }
                if (isset($userDetails ['device_token'])) {
                    $user->device_token = $userDetails ['device_token'];
                }

                $user->save();
                // array_combine_function
                $preference = new PlaylistRepository(new Playlist(), new MypreferencesVideo());
                $category = Category::where('level', 1)->whereNotNull('preference_order')->where('is_active', 1)->orderBy('preference_order')->pluck('id')->toArray();
                $catType = [ ];
                foreach ($category as $k => $v) {
                    $catType [$k] = 'sub-categories';
                }
                $preference->array_combine_function($category, $catType, $user->id);
            }
        } else {
            $user = $this->loginSocialRegisteredUsers($user, $userDetails);
        }

        // BEGIN : Block to generate JWT token
        $token = JWTAuth::fromUser($user);
        $user->access_token = $token;
        $user->save();
        // END : Block to generate JWT token
        $userId = $user->makeVisible('id');
        $userInfo = $user->toArray();
        $userInfo = Customer::SelectRaw('*, id as is_subscribed')->where('id', $user->id)->first();
        $planInfo = $userInfo->activeSubscriber()->first();
        $userInfo = $userInfo->toArray();
        $userInfo['subscribed_plan'] = $planInfo;
        $userInfo['access_token'] = $token;
        $userInfo['notification_count'] = $this->notificationRepository->getNotificationCount($userId->id);
        $userInfo['login_type'] = $type;
        $result['data'] = $userInfo;
        return $result;
    }

    /**
     * this function is used to reset the user password and
     * assign the new password to user
     * @input username and type of communication
     *
     * @return response
     */
    public function forgotPassword()
    {   
        $siteUrl = ($this->request->type)?env('WEB_SITE_AUDIO_URL'):env('WEB_SITE_URL');
        $this->setRules([ 'email' => 'required|exists:customers,email' ]);
        $this->setMessages('email.exists', trans('customer::customer.email_not_registered'));
        $this->_validate();
        $newPassword = str_random(8);
        $user = $this->_customer->where('email', $this->request->email)->first();
        $user->forgot_password = $newPassword;
        $user->save();
        if (!empty($user) && count($user->toArray()) > 0) {
            $this->email = $this->email->fetchEmailTemplate('forgot_password');
            $this->email->subject = str_replace(['##SITE_NAME##'], [config ()->get ( 'settings.general-settings.site-settings.site_name' )], $this->email->subject);
            $this->email->content = str_replace([ '##USERNAME##','##FORGOTPASSWORD##' ], [ $user->name, $siteUrl.'reset-password' . '/' . $user->forgot_password ], $this->email->content);
            $this->notification->email($user, $this->email->subject, $this->email->content);
        }
        return true;
    }
    /**
     * Function to add subscription
     *
     * @return boolean
     */
    public function addSubscription()
    {
        $custId = $this->request->id;
        $customerObj = $this->_customer->find($custId);
        if (! is_object($customerObj)) {
            return false;
        }
        $fromDate = $this->request->start_date;
        $planId = $this->request->subscription_plan;
        $orderId = $this->request->orderid;
        $getPlan = SubscriptionPlan::where('id', $planId)->first();
        $duration = $getPlan->duration;
        $planName = $getPlan->name;
        $date = Carbon::createFromFormat('d-m-Y', $fromDate);
        $fromDate = Carbon::createFromFormat('d-m-Y', $fromDate)->format('Y-m-d');
        $endDate = $date->addDays($duration);
        $customerObj->expires_at = $endDate;
        $customerObj->save();
        $subscriber = new Subscribers();
        $subscriber->subscription_plan_id = $planId;
        $subscriber->customer_id = $custId;
        $subscriber->start_date = $fromDate;
        $subscriber->end_date = $endDate;
        $subscriber->is_active = 1;
        $subscriber->save();
        $paymentTrans = new PaymentTransactions();
        $paymentTrans->payment_method_id = 2;
        $paymentTrans->customer_id = $custId;
        $paymentTrans->status = "Success";
        $paymentTrans->transaction_message = "Success";
        $paymentTrans->transaction_id = $orderId;
        $paymentTrans->response = "Success";
        $paymentTrans->plan_name = $planName;
        $paymentTrans->subscriber_id = $custId;
        $paymentTrans->subscription_plan_id = $planId;
        $paymentTrans->save();
        return true;
    }
}
