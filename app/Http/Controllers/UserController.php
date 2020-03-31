<?php


namespace App\Http\Controllers;

use App\Admin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends BaseController
{
    protected $user_id = '';
    protected $password = '';

    function __construct()
    {
        Config::set('jwt.user', User::class);
        Config::set('jwt.identifier', 'user_id');
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ]]);
    }


    public function register(Request $request) {
        $us = User::find($request->user_id);
        if(!$us) {
            $input = $request->only('user_id','name','password');
            $user = new User();
            $user->user_id = $input['user_id'];
            $user->name = $input['name'];
            $user->password = bcrypt($input['password']);
            $user->save();
            return ['msg' => 'Đăng ký thành công',
                'RequestSuccess' => true];
        }
        return ['msg' => 'Tài khoản đã tồn tại',
            'RequestSuccess' =>  false];
    }

    public function login(Request $request) {
        $user = User::with('card')->find($request->input('user_id'));
        $error = [
            'msg' => 'Tài khoản hoặc mật khẩu không đúng',
            'RequestSuccess' => false
        ];
        if($user && Hash::check($request->password, $user->password)) {
            $credentials = $request->only('user_id', 'password');
            $token = JWTAuth::attempt($credentials);

            $data = [
                'token' => $token,
                'user' => $user,
                'RequestSuccess' => true
            ];
            return $data;
        } else {
            return $error;
        }
        return $error;
    }

    public function getUserInfo(Request $request) {

        //JWTAuth::invalidate($request->token);
        $user = $request->user;//User::find($request->user->user_id);
        $token = JWTAuth::FromUser($user);
        $data = [
          'user' => $user,
            'token' => $token
        ];



        return response()->json($data,
            200,
            ['Content-type'=> 'application/json; charset=utf-8'],
            JSON_UNESCAPED_UNICODE);
    }


}

