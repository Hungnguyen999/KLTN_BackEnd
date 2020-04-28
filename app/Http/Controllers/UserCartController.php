<?php


namespace App\Http\Controllers;

use App\Cart;
use App\Cart_Course;
use App\InstructorCourse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

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
        if(InstructorCourse::find($request->course_id)) {
            if(!Cart::where('user_id', $user->user_id)->where('course_id', $request->course_id)) {
                $cart = new Cart($request->all());
                $cart->save();
                return [
                    'msg' => 'Thêm thành công',
                    'RequestSuccess' => true
                ];
            }
            return [
                'msg' => 'Khóa học đã có trong giỏ hàng',
                'RequestSuccess' => false
            ];
        }
        return [ 'msg' => 'Không tìm thấy khóa học', 'RequestSuccess' => false ];



//        $cart = Cart::where("user_id",$user->user_id)->count();
//        if($cart == 0){
//            $cart = new Cart();
//            $cart->user_id = $user->user_id;
//            $cart->course_id = $request->course_id;
//            $cart->save();
//
//            // $cart_course = new Cart_Course();
//            // $cart_course->user_id = 'hung';
//            // $cart_course->course_id = $request->course_id;
//            // $cart_course->save();
//
//            return [
//                'RequestSuccess'=>true,
//                'msg'=>'Thêm thành công',
//                'data'=> DB::table('cart')->where('user_id','oke@gmail.com')->get()
//            ];
//        }
//        else
//        {
//            $temp = Cart::where('user_id',$user->user_id)->where('course_id',$request->course_id)->get();
//            if($temp->count() != 0) {
//                return [
//                    'RequestSuccess'=>false,
//                    'msg'=>'Đã có khóa học trong cart',
//                ];
//            }
//            else
//            {
//                $cart = new Cart();
//                $user_id = $user->user_id;
//                $course_id = $request->course_id;
//                $cart->user_id = $user_id;
//                $cart->course_id = $course_id;
//                $cart->save();
//                return [
//                    'RequestSuccess'=>true,
//                    'msg'=>'Thêm thành công',
//
//                ];
//            }
        // $temp = Cart_Course::where('user_id','hung')->where('course_id',$request->course_id)->get();
        // if($temp->count() != 0) {
        //     return [
        //         'RequestSuccess'=>false,
        //         'msg'=>'Đã có khóa học trong cart',
        //     ];
        // }
        // else {
        //     $cart_course = new Cart_Course();
        //     $cart_course->user_id = 'hung';
        //     $cart_course->course_id = $request->course_id;
        //     $cart_course->save();
        //     return [
        //         'RequestSuccess'=>true,
        //         'msg'=>'Thêm thành công',
        //         'data'=> DB::table('Cart')->where('user_id','hungg')->get()
        //     ];
        // }
    }
    public function getCarts(Request $request)
    {

        $user = $request->user;
        $cart = DB::table('cart')->where('user_id',$user->user_id)->select('course_id');
        //$courses_in_cart = DB::table('course')->where('course',$cart)->get();
        $course_in_cart = DB::table('cart')
            ->join('instructor_course', 'cart.course_id', '=', 'instructor_course.course_id')
            ->join('pricetier','pricetier.priceTier_id', '=','instructor_course.priceTier_id')
            ->where('cart.user_id','=',$user->user_id)
            ->select('instructor_course.course_id','instructor_course.name','pricetier.priceTier')->get();

        $total_in_cart = DB::table('cart')
            ->join('instructor_course', 'cart.course_id', '=', 'instructor_course.course_id')
            ->join('pricetier','pricetier.priceTier_id', '=','instructor_course.priceTier_id')
            ->where('cart.user_id','=',$user->user_id)
            ->sum('pricetier.priceTier');
        return [
            'RequestSuccess'=>true,
            'carts'=> $course_in_cart,
            'total' => $total_in_cart,
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
            return [
                'RequestSuccess'=>true,
                'msg'=> 'Đã xóa khóa học khỏi giỏ hàng',
                'carts' => $course_in_cart = DB::table('cart')
                    ->join('instructor_course', 'cart.course_id', '=', 'instructor_course.course_id')
                    ->join('pricetier','pricetier.priceTier_id', '=','instructor_course.priceTier_id')
                    ->where('cart.user_id','=',$user->user_id)
                    ->select('instructor_course.course_id','instructor_course.name','pricetier.priceTier')->get()
                //sao khi xoa truyen ve 1 list cart
            ];
        }
        return [
            'RequestSuccess'=>false,
            'msg'=> 'đã có lỗi xảy ra'
        ];
    }
}