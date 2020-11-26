<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Contus\Customer\Models\Customer;
use Contus\Base\Helpers\StringLiterals;


class CustomerTokenAuthenticate {
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Guard $auth
     * @return void
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $request->session()->forget('multiple_login');
        if ($this->auth->user () && $this->auth->user ()->access_token !== $request->session ()->get ( 'access_token' )) {
            Auth::logout();
            $request->session()->flash(StringLiterals::MULTIPLE_LOGIN, trans('video::videos.message.multiplelogin'));
         }
        return $next ( $request );
    }
}
