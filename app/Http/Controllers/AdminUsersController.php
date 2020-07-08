<?php

namespace App\Http\Controllers;
use App\Admin;
use App\AdminType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminUsersController extends BaseController{
    function __construct()
    {
        Config::set('jwt.user', Admin::class);
        Config::set('jwt.identifier', 'admin_id');
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }
    public function getListUsers(){
        $users = Admin::select('admin_id','name','phone','address','created_at','admintype_id','disable')->get();
        $admin_type = AdminType::get();
        return [
            'list' => $users,
            'type' => $admin_type,
            'msg' => "Get list user information completed successfully !"
        ];
    }
    public function insertUser(Request $request){
        $ad = Admin::find($request->admin_id);
        if(!$ad) {
            $admin = new Admin($request->all());
            $admin->admin_id = $request->admin_id;
            $admin->name = $request->name;
            $admin->phone = $request->phone;
            $admin->address = $request->address;
            $admin->password =  bcrypt("123456");
            $admin->admintype_id = $request->admintype_id;
            $admin->save();
            return ['msg' => 'Registration completed successfully !',
                'RequestSuccess' => true];
        }
        return ['msg' => 'Tài khoản đã tồn tại',
            'RequestSuccess' =>  false];
    }
    public function editUser(Request $request){
        $user = Admin::find($request->admin_id);
        if(!$user){
            return [
                'RequestSuccess' => false,
                'msg' => 'Error !'
            ];
        }
        else{
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->admintype_id = $request->admintype_id;
            $user->save();
            return [
                'msg' => 'User information has been updated !',
                'user' => $user,
                'RequestSuccess' => true
            ];
        }
        
    }
    public function deleteUser(Request $request){
        $user = Admin::find($request->admin_id);
        if(!$user){
            return [
                'RequestSuccess' => false,
                'msg' => "Error !"
            ];
        }
        else{
            $user->disable = true;
            $user->save();
            return [
                'RequestSuccess' => true,
                'msg' => "User has been disabled !"
            ];
        }
    }
    public function activeUser(Request $request){
        $admin = $request->admin_id;
        if(!$admin){
            return [
                'RequestSuccess' => false,
                'msg' => "lỗi đăng nhập"
            ];
        }
        else{
            $user = Admin::find($request->admin_id);
            if(!$user){
                return [
                    'RequestSuccess' => false,
                    'msg' => "Đã có lỗi xảy ra"
                ];
            }
            else{
                $user->disable = false;
                $user->save();
                return [
                    'RequestSuccess' => true,
                    'msg' => "Đã kích hoạt tài khoản"
                ];
            }
        }
    }
}