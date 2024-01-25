<?php

namespace App\Http\Middleware;

use App\Http\Traits\HelperApi;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{

    use HelperApi;
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // print($user);
            // die;
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->onError(401,  'Token is Invalid', '');
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->onError(401,  'Token is Expired', '');
            } else {
                return $this->onError(401,  'Authorization Token not found', '');
            }
        }
        return $next($request);
    }
}
