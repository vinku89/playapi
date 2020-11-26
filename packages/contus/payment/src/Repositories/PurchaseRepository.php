<?php

/**
 * Purchase Repository
 *
 * To manage the functionalities related to the Purchase module from Purchase Controller
 *
 * @name Purchase
 * @vendor Contus
 * @package Purchase
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Repositories;

use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Http\Request;
use Contus\Customer\Models\Customer;
use Contus\Payment\Models\PaymentTransactions;
use Contus\Base\Helpers\StringLiterals;
use Contus\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Payment\Repositories\CardRepository;
use Contus\Customer\Models\Subscribers;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Payment\Models\PaymentMethod;
use Contus\Payment\Models\Card;
use Contus\User\Models\Setting;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Video\Models\Video;
use Illuminate\Support\Str;

class PurchaseRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_transaction;

    /**
     * Class property to hold the key which hold the customer object
     *
     * @var object
     */
    protected $_customer;

    /**
     * Class property to hold the key which hold the card object
     *
     * @var object
     */
    protected $cardRepository;
    /**
     * Constructor function
     *
     * @param PaymentTransactions $transaction
     * @param CustomerRepository $customer
     * @param SubscriptionRepository $subscription
     * @param CardRepository $cardRepository
     */
    public function __construct(PaymentTransactions $transaction, CustomerRepository $customer, SubscriptionRepository $subscription, CardRepository $cardRepository, EmailTemplatesRepository $email, NotificationRepository $notification)
    {
        parent::__construct();
        $this->_transaction = $transaction;
        $this->_subscription = $subscription;
        $this->_customer = $customer;
        $this->cardRepository = $cardRepository;
        $this->email = $email;
        $this->notification = $notification;
        $this->video = new Video();
    }
    /**
     * Store a newly created payment transaction .
     *
     * @vendor Contus
     *
     * @package Purchase
     * @param $id input
     * @return boolean
     *
     */
    public function addTransactions($package_id = '', $user = '', $decryptValues = '')
    {
        $transactions = new PaymentTransactions();
        $dataSize = sizeof($decryptValues);
        for ($i = 0; $i < $dataSize; $i ++) {
            $transaction = explode('=', $decryptValues [$i]);
            if ($i == 3) {
                $transactions->status = $transaction [1];
                $transactions->transaction_message = $transaction [1];
                $transactions->response = $transaction [1];
            }
        }
        $orderId = explode('=', $decryptValues [0]) [1];
        $transactions->payment_method_id = 2;
        $transaction = explode('=', $decryptValues [26]);
        $transactions->customer_id = $user->id;
        $transaction = explode('=', $decryptValues [17]);
        $transactions->phone = $transaction [1];
        $transaction = explode('=', $decryptValues [18]);
        $transactions->email = $transaction [1];
        $transaction = explode('=', $decryptValues [11]);
        $transactions->name = $transaction [1];
        $transaction = explode('=', $decryptValues [1]);
        $transactions->transaction_id = $transaction [1];
        $transactions->creator_id = $transactions->customer_id;
        $transactions->subscriber_id = $transactions->customer_id;
        $transactions->subscription_plan_id = $orderId;
        $transaction = explode('=', $decryptValues [26]);
        $transactions->plan_name = $transaction [1];
        if ($transactions->save()) {
            if (!empty(authUser()->id)) {
                authUser()->loginUsingId($transactions->customer_id);
            }
            if ($transactions->status == 'Success') {
                $this->_subscription->addSubscriber($orderId);
            }
            return $transactions;
        } else {
            return false;
        }
    }
    /**
     * fetch all the transactions
     *
     * @vendor Contus
     *
     * @package Purchase
     * @return array
     */
    public function getAllTransactions()
    {
        if (authUser()->id == 1) {
            return $this->_transaction->paginate(10)->toArray();
        } else {
            return $this->_transaction->with('getTransactionUser')->where('customer_id', authUser()->id)->paginate(10)->toArray();
        }
    }
    /**
     * fetches one transaction
     *
     * @vendor Contus
     *
     * @package Purchase
     * @param int $transactionId
     * @return object
     */
    public function getTransaction($transactionId)
    {
        return $this->_transaction->find($transactionId);
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     * @vendor Contus
     *
     * @package Payment
     * @return Contus\Payment\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_transaction)->setEagerLoadingModels(['video']);
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($transactionBuilder)
    {
        /*
         * updated the all user record only an superadmin user.
         */
        if (config()->get('auth.providers.users.table') === 'customers') {
            $transactionBuilder->where('customer_id', authUser()->id);
        } else {
            if (authUser()->id != 1) {
                $transactionBuilder->where('id', authUser()->id)->orWhere('parent_id', authUser()->id);
            }
        }
        $transactionBuilder->where('transaction_id','!=','')->where('video_id','>',0)->where('customer_id', authUser()->id)->where(function ($query) {
            $query->where('status', '=', 'Paid')
                  ->orWhere('status', '=', 'Expired');
        });
        return $transactionBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Payment
     * @param mixed $builderTransaction
     * @return \Illuminate\Database\Eloquent\Builder $builderTransaction The builder object of users grid.
     */
    protected function searchFilter($builderTransaction)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [ ];

        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ($searchRecordUsers as $key => $value) {
            switch ($key) {
                case 'slug':
                    $builderTransaction = $builderTransaction->whereHas('getTransactionUser', function ($q) use ($value) {
                        $q->where('name', 'like', '%' . $value . '%');
                    });
                    break;
                case 'is_active':
                    if ($key == 'is_active' && $value == 'all') {
                        break;
                    }

                    // no break
                default:
                    $builderTransaction = $builderTransaction->where($key, 'like', "%$value%");
            }
        }
        return $builderTransaction;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Payment
     * @return array
     */
    public function getGridHeadings()
    {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans('payment::transaction.transaction_id'),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans('payment::transaction.customer_name'),StringLiterals::VALUE => '','sort' => false ],

        [ 'name' => trans('payment::transaction.status'),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans('payment::transaction.transactionOn'),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans('payment::transaction.action'),StringLiterals::VALUE => '','sort' => false ] ] ];
    }

    /**
     * Function to fetch all the details of a transaction from the database.
     *
     * @param integer $id
     * The id of the transaction whose data are to be fetched.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|NULL The information of the video.
     */
    public function getCompleteTransaction($id)
    {
        return $this->_transaction->with([ 'getTransactionUser','getPaymentMethod' ])->where('id', $id)->first();
    }

    public function saveTransaction()
    {
        $this->setRules([
          'subscription_plan_id' => 'required',
          'payment_method_id' => 'required|integer|card_validate:card_number,cvv,month,year,type,name'
        ]);
        $this->_validate();
        $response = $this->request->all();
        if (!isset($response['card_id']) && (isset($response['is_save']) && $response['is_save'])) {
            $cardData = $this->cardRepository->saveCards();
            $cardId = $cardData['data']['saved_card']['id'];
        } else {
            $cardId = isset($response['card_id']) ? (int) $response['card_id'] : null;
        }
        $return['success'] = false;
        $return['response'] = [];
        $user = Auth::user();

        $subscriptionPlan = SubscriptionPlan::where($this->getKeySlugorId(), $response['subscription_plan_id'])->first();

        if(!empty($subscriptionPlan)) {

            Subscribers::where('customer_id', $user->id)->update(['is_active' => 0]);

            $subscribers = Subscribers::updateOrCreate(
                ['customer_id'=>$user->id],
                [
                    'customer_id' => $user->id,
                    'subscription_plan_id' => $subscriptionPlan->id,
                    'start_date' => date('Y-m-d'),
                    'end_date' => date('Y-m-d', strtotime(date('Y-m-d'). ' + '.$subscriptionPlan->duration.' days')),
                    'is_active' => 1,
                    'creator_id' => $user->id,
                ]
            );

            $paymentMethod = PaymentMethod::find($response['payment_method_id']);
            $transactions = new PaymentTransactions();
            $transactions->name = $user->name;
            $transactions->email = $user->email;
            $transactions->phone = $user->phone;
            $transactions->payment_method_id = $paymentMethod->id;
            $transactions->customer_id = $user->id;
            $transactions->transaction_id = $this->generateRandomString();
            $transactions->status = 'Paid';
            $transactions->subscription_plan_id = $subscriptionPlan->id;
            $transactions->amount = $subscriptionPlan->amount;
            $transactions->subscriber_id = $subscribers->id;
            $transactions->plan_name = $subscriptionPlan->name;
            $transactions->card_id = $cardId;
            $transactions->response = json_encode($response);
            $transactions->creator_id = $user->id;
            $transactions->updator_id = $user->id;


            if ($transactions->save()) {

                $mailSubject = config ()->get ( 'settings.general-settings.site-settings.site_name' );
                $return['success'] = true;
                $return['data']['transaction'] = $transactions->toArray();
                $notifyAdmin = config()->get('settings.settings.website-settings.admin_mail_notification');
                if ($notifyAdmin === 'YES') {
                    $adminMail['name'] = $mailSubject;
                    $adminMail['email'] = config ()->get ( 'settings.general-settings.site-settings.site_email_id' );

                    $content = authUser()->name . ' has upgraded subscription to ' . authUser()->activeSubscriber()->first()->name;
                    $email = $this->email->fetchEmailTemplate('upgrade_mailto_admin');
                    if(!empty($email)) {
                        $email->subject = str_replace(['##SITE_NAME##'], [$mailSubject], $email->subject);
                        $email->content = str_replace([ '##NAME##','##PLAN##' ], [ authUser()->name,authUser()->activeSubscriber()->first()->name ], $email->content);
                        $this->notification->email($adminMail, $email->subject, $email->content);
                    }
                }
                $content = trans('customer::subscription.upgrade'). authUser()->activeSubscriber()->first()->name;
                $email = $this->email->fetchEmailTemplate('upgrade_mailto_customer');

                if(!empty($email)) {
                    $email->subject = str_replace(['##SITE_NAME##'], [$mailSubject], $email->subject);
                    $email->content = str_replace([ '##USERNAME##','##PLAN##' ], [ authUser()->name,authUser()->activeSubscriber()->first()->name ], $email->content);
                    $this->notification->email(authUser(), $email->subject, $email->content);
                }
            }
        }
        return $return;
    }

    public function removeSubscription() {
        $response = [];
        $response['success'] = true;
        $user = authUser();
        $userPlan = authUser()->activeSubscriber()->first()->name;
        try {
            Subscribers::where('customer_id', $user->id)->update(['is_active' => 0]);
            $response['message'] = trans('customer::subscription.delete.success');
            $mailSubject = config ()->get ( 'settings.general-settings.site-settings.site_name' );
            $email = $this->email->fetchEmailTemplate('unsubscription_mailto_customer');
            if(!empty($email)) {
                $email->subject = str_replace(['##SITE_NAME##'], [$mailSubject], $email->subject);
                $email->content = str_replace([ '##USERNAME##','##PLAN##' ], [ $user->name,  $userPlan], $email->content);
                $this->notification->email($user, $email->subject, $email->content);
            }
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = trans('customer::subscription.delete.error');
        }
        return $response;
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getAllPlans()
    {
        $result['error'] = false;
        $result['message'] = '';
        $result['data']     = '';

        try {
            $data ['subscription'] = SubscriptionPlan::SelectRaw('*, id as is_subscribe')->where('is_active',1)->orderBy('amount', 'asc')->get();

            
            $data ['subscribed_plan'] = (!empty(authUser()->id)) ? auth()->user()->activeSubscriber()->first(): new \stdclass();
            $data ['plan_duration_left'] = '';
            $result['data'] = $data;
        } catch (\Exceptions $e) {
            $result['error'] = true;
            $result['message'] = trans('customer::subscription.showError');
        }
        return $result;
    }

    /**
     * fetches one transaction
     *
     * @vendor Contus
     *
     * @package Purchase
     * @param int $transactionId
     * @return object
     */
    public function getTransactionDetails()
    {
        $data ['transaction'] = $this->_transaction->select('payment_method_id', 'transaction_id', 'plan_name', 'updated_at')->with(['getPaymentMethod'])->where('transaction_id', $this->request->id)->first();
        return $data;
    }
    /**
     * Method to handle the video TVOD for the customer
     * 
     * @ereturn array
     */
    public function handleVideoPayment(){
        $globalVideoCount = Setting::where('setting_name', '=', 'video_view_count')->first();
        $this->setRules(['slug' => 'required','price' => 'required']);
        if ($this->_validate()) {            
            $payment = new PaymentTransactions();
            $paymentResult = array();
            $transactionID = Str::random(11);
            $slug = $this->request->slug;
            $getVideoID = $this->video->select('id')->where($this->getKeySlugorId(),$slug)->first();
            $videoID = $getVideoID->id;
            $payment->customer_id = authUser()->id;
            $payment->name = authUser()->name;
            $payment->email = authUser()->email;
            $payment->phone = authUser()->phone;
            $payment->video_id = $videoID;
            $payment->amount = $this->request->price;
            $payment->plan_name = '';
            $payment->status = 'Paid';
            $payment->global_view_count = $globalVideoCount->setting_value;
            $payment->transaction_id = $transactionID;
            $payment->payment_method_id = 1;
            $payment->created_at = date('Y-m-d H:i:s');
            $payment->updated_at = date('Y-m-d H:i:s');
            try {
                $paymentResult['status'] = $payment->save();
                $paymentResult['data']['transaction'] = $payment->toArray();
            } catch (\Exception $e){
                $paymentResult['status'] = false;
                $paymentResult['message'] = $e->getMessage();
            }        }
        return $paymentResult;
    }
}
