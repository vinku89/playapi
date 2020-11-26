<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Contus\Customer\Models\Customer;
use Auth;
use Log;
class ApiCustomerAuthenticate
{
    /**
     * Class property to hold the request header
     *
     * @var obj
     */
    protected $header = null;
    /**
     * Class property the access token error
     *
     * @var int
     */
    protected $access_token_error = 0;
    /**
     * Class property the access token
     *
     * @var string
     */
    protected $access_token = null;
    /**
     * Class property the public access token
     *
     * @var string
     */
    protected $user_id = null;
    /**
     * Create a new filter instance.
     */
    protected $request_type = null;
    /**
     * It is used to differenciate the request type.
     */
    public function __construct()
    {
        $this->header = Request::header();
    }
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info("ApiCustomerAuthenticate start");
        $this->request_type = $this->splitHeaderTokens('x-request-type');
        $token = $this->splitHeaderTokens('authorization');
        $token = ($token != '') ? str_replace('Bearer ', '', $token) : '';
        if ($token != '') {
            $this->access_token = $token;
            $user = Customer::where('access_token', $this->access_token)->where('id', auth()->user()->id)->where('is_active', 1)->first();
            if (empty($user)) {
                return Response::json(array('error' => true,'statusCode' => 403,'status' => 'error','message' => trans('base::general.otherdevice_login')), 403);
            }
            if (! is_null($this->request_type) && ($this->request_type == 'mobile' || $this->request_type == 'web')) {
                auth()->loginUsingId($user->id);
            } else {
                if (auth()->user()) {
                    if ((auth()->user()->is_active === 0) || auth()->user()->access_token !== request()->session()->get('access_token')) {
                        request()->session()->flash('multiple_login', trans('base::general.otherdevice_login'));
                        auth()->logout();
                        return Response::json(array('error' => true,'statusCode' => 403,'status' => 'error','message' =>trans('base::general.otherdevice_login')), 403);
                    }
                }
            }
            $request ['user_id'] = $this->user_id;
        }
        Log::info("ApiCustomerAuthenticate end");
        return $next($request);
    }
    /**
     * Split header values based on the type
     *
     * @param
     * $type
     *
     * @return bool | string
     */
    public function splitHeaderTokens($type)
    {
        if (isset($this->header [$type] [0]) && ! empty($this->header [$type] [0])) {
            return $this->header [$type] [0];
        }
        return false;
    }
}
