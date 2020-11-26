<?php
namespace App\Http\Middleware;

/*use Closure;
use JWTAuth;
use Exception;*/

use App;
use Closure;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
// use Tymon\JWTAuth\JWTAuth;
use JWTAuth;
use Log;
class authJWT
{
    protected $auth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->auth = $jwtAuth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $nexttoken
     * @return mixed
     */
    public function handle($request, Closure $next, $novalidate = false)
    {
        Log::info("authJWT start");
        $method = $request->method();
        if ((!$token = JWTAuth::setRequest($request)->getToken()) && !$novalidate) {
            return response()->json(['error' => true, 'message' => 'Please login to continue', 'method' => $method, 'statusCode' => 401], 401);
        }
        if ($request->header('Authorization')) {
            try {
                $user = JWTAuth::toUser($token);
            } catch (TokenExpiredException $e) {
                return response()->json(['error' => true, 'message' => 'Session expired, login to continue', 'method' => $method, 'statusCode' => 401], 401);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Invalid login session', 'method' => $method, 'statusCode' => 401], 401);
            }
            if (!$user) {
                return response()->json(['error' => true, 'message' => 'Your account has been deleted', 'statusCode' => 401], 401);
            }
        }
        Log::info("authJWT end");
        return $next($request);
    }
}
