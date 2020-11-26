<?php

/**
 * Customer Auth Controller
 *
 * To manage the functionalities related to the Customer api auth methods
 *
 * @name CustomerAuthController
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Api\Controllers\Customer;

use Contus\Base\ApiController;
use Contus\Customer\Repositories\CustomerRepository;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\Collection;
use Contus\Customer\Models\SubscriptionPlan;

class CustomerAuthController extends ApiController {
    /**
     * Construct method
     */
    public function __construct(CustomerRepository $customerRepository) {
        parent::__construct ();
        $this->repository = $customerRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );

        $this->repoArray = ['repository'];
    }

    /**
     * function to register customer information using api
     *
     * @return \Contus\Base\response
     */
    public function apiRegister() {
      if(!$this->request->header('User-Agent')){
        return false;
      }
        if ($this->request->login_type == 'normal') {
            $customer = $this->repository->addOrUpdateCustomers ();
        } else {
            $customer = $this->repository->socialRegister ();
        }
        if($customer['error']) {
            return $this->getErrorJsonResponse ( [ ], $customer['message'], 422 );
        }
        return $this->getSuccessJsonResponse (['response' => $customer['data']], trans('customer::customer.registersuccess'));
    }

    /**
     * function to login customer information using api
     *
     * @return \Contus\Base\response
     */
    public function apiLogin() {
        if ($this->request->login_type == 'normal') {
            $customer = $this->repository->checkCustomers ();
        } else {
            $customer = $this->repository->checksocialCustomers ();
        }
        if($customer['error']) {
            return $this->getErrorJsonResponse ( [ ], $customer['message'], 422 );
        }
        return $this->getSuccessJsonResponse (['response' => $customer['data']], trans('customer::customer.loginsuccess'));
    }

    /**
     * function to logout customer information using api
     *
     * @return \Contus\Base\response
     */
    public function apiLogout() {
        $customer = $this->repository->checkIOSCustomers ();
        return (isset ( $customer->id )) ? $this->getSuccessJsonResponse ( [], trans ( 'customer::customer.logoutsuccess' ) ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.logouterror' ), 422 );
    }

    /**
     * This function used to customer can able to reset the password through email link
     *
     * @return \Contus\Base\response
     */
    public function apiForgotpassword() {
        $forgotpassword = $this->repository->forgotPassword ();
        $response = [ 'message' => trans ( 'customer::customer.forgotsuccess' ) ];
        return (isset ( $forgotpassword )) ? $this->getSuccessJsonResponse ( $response ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.loginerror' ), 422 );
    }

    /**
     * function to change password
     *
     * @return \Contus\Base\response
     */
    public function apiPassChange() {
        $customer = $this->repository->changePassword ();
        if ($this->request->header ( 'x-request-type' ) == 'mobile') {
            $response = [ 'response' => $customer,'message' => trans ( 'customer::customer.changepassword.success' ) ];
        } else {
            $response = [ 'message' => trans ( 'customer::customer.changepassword.success' ) ];
        }
        return (isset ( $customer->id )) ? $this->getSuccessJsonResponse ( $response ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.changepassword.incorrect' ), 422  );
    }

    /**
     * function to generate otp for reset
     *
     * @return \Contus\Base\response
     */
    public function apiReset() {
        $customer = $this->repository->generateResetPassword ();
        return ($customer) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.emailreset.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.emailreset.error' ), 422  );
    }

    /**
     * function to validate otp and reset password
     *
     * @return \Contus\Base\response
     */
    public function apiResetPass() {
        
        $customer = $this->repository->otpResetPassword ();
        return ($customer) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.changepassword.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.changepassword.otperror' ), 422  );
    }

    /**
     * function to validate otp and reset password
     *
     * @return \Contus\Base\response
     */
    public function apiResetPassword() {
        $customer = $this->repository->resetPassword ();
        if(!empty($customer['error'])) {
            return $this->getErrorJsonResponse ( [ ], $customer['message'], 422  );
        }
        return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.changepassword.success' ) ] );
    }

    /**
     * function to validate otp and reset password
     *
     * @return \Contus\Base\response
     */
    public function apiVerifyResetPassword() {
        $customer = $this->repository->verifyResetPassword ();
        if(!empty($customer['error'])) {
            return $this->getErrorJsonResponse ( [ ], $customer['message'], 422  );
        }
        return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'passwords.token_verified' ) ] );
    }



    /**
     * function to update one customer information
     *
     * @return json
     */
    public function updateProfile() {
        $customerId = auth ()->user ()->id;
        $update = $this->repository->addOrUpdateCustomers ( $customerId );
        if($update['error']) {
            return $this->getErrorJsonResponse ( [ ], $update['message'], 401 );
        }
        return $this->getSuccessJsonResponse (['response' => $update['data']], trans('customer::customer.updated'));
    }

    /**
     * To get the user info.
     *
     * @return json
     */
    public function getInfo() {
     return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' => $this->repository->getRules (),'exams' => Collection::where ( 'is_active', 1 )->get (),'subscription_plans' => SubscriptionPlan::where ( 'is_active', 1 )->get ()->makeVisible('id') ] ] );
    }
    /**
     * Function to add subscription
     *
     * @return json
     */
    public function addSubcription() { 
        $customer = $this->repository->addSubscription ();
        return ($customer) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.subscription.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.subscription.error' ), 422  );
    }

    /**
     * Function to update device token
     *
     * @return json
     */
    public function updateDeviceToken() { 
        $customer = $this->repository->updateDeviceToken ();
        return ($customer) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.updatedError' ), 422  );
    }
}
