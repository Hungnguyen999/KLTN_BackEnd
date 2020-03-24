<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$auth = JWTAuth::parseToken()) {
        throw Exception('JWTAuth unable to parse token from request');
        } else {
            $token = $request->token;
            try {
                $user = JWTAuth::toUser($token);
                $request->merge([
                    'user' => $user
                ]);
            }catch (JWTException $e) {
                if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    return response()->json(['token_expired'], $e->getStatusCode());
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
                }else{
                    return response()->json(['error'=>'Token is required']);
                }
            }
        }
        return $next($request);
    }
}
