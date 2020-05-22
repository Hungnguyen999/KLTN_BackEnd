<?php


namespace App\Http\Controllers;

use App\Cart;
use App\Cart_Course;
use App\InstructorCourse;
use App\StudentCourse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserCartController extends BaseController
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

    public function addToCart(Request $request){
        $user = $request->user;
        $course  = InstructorCourse::with('priceTier')->find($request->course_id);
        if($course) {
            $priceTier = $course->priceTier;
            if($priceTier->priceTier_id == 0) {
                $stuCourse = StudentCourse::where('user_id', $user->user_id)->where('course_id', $course->course_id)->first();
                if(!$stuCourse) {
                    $stuCourse = new StudentCourse();
                    $stuCourse->user_id = $user->user_id;
                    $stuCourse->course_id = $course->course_id;
                    $stuCourse->save();
                    return [
                        'msg' => 'Thêm thành công',
                        'RequestSuccess' => true
                    ];
                } else {
                    return [
                        'msg' => 'Khóa học đã tồn tại trong kho',
                        'RequestSuccess' => false
                    ];
                }
            } else {
                $stuCourse = StudentCourse::where('user_id', $user->user_id)->where('course_id', $course->course_id)->first();
                if($stuCourse) {
                    return [
                        'msg' => 'Khóa học đã tồn tại trong kho',
                        'RequestSuccess' => false
                    ];
                }else {
                    if(!Cart::where('user_id', $user->user_id)->where('course_id', $request->course_id)->first()) {
                        $user = $request->user;
                        $cart = new Cart();
                        $cart->user_id = $user->user_id;
                        $cart->course_id = $request->course_id;
                        $cart->save();

                        $courseList = DB::table('cart')->where('user_id',$user->user_id)->select('course_id')->get();
                        $tempCourseList = [];
                        foreach ($courseList as $course) {
                            $temp = InstructorCourse::with('priceTier', 'instructor')->find($course->course_id);
                            array_push($tempCourseList, $temp);
                        }
                        return [
                            'msg' => 'Thêm thành công',
                            'RequestSuccess' => true,
                            'list' => $tempCourseList
                        ];
                    }
                    return [
                        'msg' => 'Khóa học đã có trong giỏ hàng',
                        'RequestSuccess' => false
                    ];
                }
            }
        }
        return [ 'msg' => 'Không tìm thấy khóa học', 'RequestSuccess' => false ];
    }
    public function getCarts(Request $request)
    {
        $user = $request->user;
        $courseList = DB::table('cart')->where('user_id',$user->user_id)->select('course_id')->get();

        $tempCourseList = [];
        foreach ($courseList as $course) {
            $temp = InstructorCourse::with('priceTier', 'instructor')->find($course->course_id);
            array_push($tempCourseList, $temp);
        }
        return [
            'list' => $tempCourseList
        ];
    }
    public function deleteCarts(Request $request){
        $user = $request->user;
        $cart_user_id = $user->user_id; // dat ten bien cho thong nhat
        $cart_course_id = $request->course_id;


        $cart = DB::table('cart')
            ->where('user_id','=',$cart_user_id)
            ->where('course_id','=',$cart_course_id)
            ->first();
        if($cart){
            DB::table('cart')
                ->where('user_id','=',$cart_user_id)
                ->where('course_id','=',$cart_course_id)->delete();

            $courseList = DB::table('cart')->where('user_id',$user->user_id)->select('course_id')->get();
            $tempCourseList = [];
            foreach ($courseList as $course) {
                $temp = InstructorCourse::find($course->course_id)->with('priceTier');
                array_push($tempCourseList, $temp);
            }
            return [
                'RequestSuccess'=>true,
                'list' => $courseList
            ];
        }
        return [
            'RequestSuccess'=>false,
            'msg'=> 'Không tìm thấy sản phẩm trong giỏ hàng của bạn'
        ];
    }
}