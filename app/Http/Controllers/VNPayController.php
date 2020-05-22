<?php


namespace App\Http\Controllers;


use App\BillStudentCourse;
use App\Cart;
use App\InstructorCourse;
use App\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VNPayController extends BaseController
{
    public function create(Request $request)
    {
        $user = $request->user;
        $carts = Cart::where('user_id', $user->user_id)->get();
        $totalPrice = 0;
        Session::put('user_id', $user->user_id);
        foreach ($carts as $cart) {
            $course = InstructorCourse::with('priceTier')->find($cart->course_id);
            if($course) {
                $totalPrice += $course->priceTier->priceTier;
            } else {
                return ['msg' => 'Đã có lỗi xảy ra, vui lòng xem lại giỏ hàng', 'RequestSuccess' => false];
            }
        }

        session(['cost_id' => $request->id]);
        session(['url_prev' => url()->previous()]);
        //SZM993R4
        //JJUZVZFGCCQFLRAHFQYAXHNTPOWAMIDT
        $vnp_TmnCode = "8ZOGO9RW"; //Mã website tại VNPAY
        $vnp_HashSecret = "KDASWVSUZEFSCKKWZPRQEEKFIAHQZULP"; //Chuỗi bí mật
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://goodlearning.com/callbackVPN";
        $vnp_TxnRef = date("YmdHis"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn phí dich vụ";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $totalPrice * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
    }

    public function callback(Request $request) {
        $user_id = Session::get('user_id');
        Session::put('user_id', 0);
        if($request->vnp_ResponseCode == '00') {
            $carts = Cart::where('user_id', $user_id)->get();
            foreach ($carts as $cart) {
                $stuCourse = new StudentCourse();
                $stuCourse->user_id = $user_id;
                $stuCourse->course_id = $cart->course_id;
                $stuCourse->save();


                $bill = BillStudentCourse::where('user_id', $user_id)->where('course_id', $cart->course_id)->first();
                $tempBill = new BillStudentCourse();
                if($bill) {
                    $tempBill->bill_student_course_id = $bill->bill_student_course_id;
                }
                $tempBill->user_id = $cart->user_id;
                $tempBill->course_id = $cart->course_id;
                $tempBill->save();

                DB::table('cart')
                    ->where('user_id', $user_id)->where('course_id', $cart->course_id)->delete();
            }
            return redirect('http://localhost:8081/mypage?state='.$request->vnp_ResponseCode);
        }
    }
}