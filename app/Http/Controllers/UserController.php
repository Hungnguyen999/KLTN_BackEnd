<?php


namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use JWTAuth;

class UserController extends BaseController
{
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
        $user = User::find($request->input('user_id'));
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
}