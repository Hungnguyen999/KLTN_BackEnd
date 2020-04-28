<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        $token = $request->token;
        try {
            $us = JWTAuth::parseToken($token);
            $user = JWTAuth::toUser($token);
            $request->merge([
                'user' => User::with('card')->find($user->user_id)
            ]);
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['errorToken' => true, 'msg' => 'Lỗi đăng nhập, vui lòng đăng nhập lại']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['errorToken' => true, 'msg' => 'Lỗi đăng nhập, vui lòng đăng nhập lại']);
            }else{
                return response()->json(['errorToken' => true, 'msg' => 'Lỗi đăng nhập, vui lòng đăng nhập lại']);
            }
        }
        return $next($request);
    }
}
