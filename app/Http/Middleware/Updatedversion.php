<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Guard;
use Log;
class Updatedversion
{
    public function handle($request, Closure $next)
    {
    	Log::info("Updatedversion");
        // $request->session ()->set ( 'updated_version', 's3Url' );
        return $next($request);
    }
}