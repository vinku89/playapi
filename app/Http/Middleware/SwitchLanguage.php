<?php

namespace App\Http\Middleware;

use Closure;

class SwitchLanguage
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    
    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!is_null(app()->make('request')->header('X-LANGUAGE-CODE'))) {
            $lang = app()->make('request')->header('X-LANGUAGE-CODE');
        }
        else {
            $lang = 'en';
        }
        app()->setLocale($lang);
        return $next($request);
    }
}