<?php
namespace App\Http\Controllers;
use App\Admin;
use App\PriceTier;
use App\MoneyType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class AdminPriceController extends BaseController{
    function __construct(){
        Config::set('jwt.user', Admin::class);
        Config::set('jwt.identifier', 'admin_id');
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }
    public function getListPriceTier(Request $request){
        $listPricetier = DB::table("priceTier")
        ->get();
        return [
            'RequestSuccess' => true,
            'list' => $listPricetier
        ];
    }
    public function getListCoursebyPrice(Request $request){
        $price = $request->price;
    
        $listCourse = DB::table("instructor_course")
        ->join("pricetier","instructor_course.priceTier_id","=","pricetier.priceTier_id")
        ->join("user","instructor_course.user_id","=","user.user_id")
        ->where("pricetier.priceTier","=",$price)
        ->select("instructor_course.name as courseName","user.name","pricetier.priceTier")
        ->get();
        return [
            'RequestSuccess' => true,
            'list' => $listCourse
        ];
    }
    public function deletePricetier(Request $request){
        $pricetier_id = $request->pricetier_id;
        $disable = $request->disable;
        $price = PriceTier::find($pricetier_id);
        if(!$price){
            return [
                'RequestSuccess' => false,
                'msg' => "Not found this item !"
            ];
        }
        else{
            if($disable == false){
                $price->disable = true;
                $price ->save();
                return [
                    'RequestSuccess' => true,
                    'msg' => "Pricetier has been disabled !"
                ];
            }
            else{
                $price->disable = false;
                $price ->save();
                return [
                    'RequestSuccess' => true,
                    'msg' => "Pricetier has been enabled !"
                ];
            }
        }
    }
    public function insertPricetier(Request $request){
        $pricetierNewValue = $request->pricetierNewValue;

        $pricetier = PriceTier::where("priceTier","=",$pricetierNewValue)->get();
   
        if(count($pricetier) == 0){
            $newprice = new PriceTier();
            $newprice->priceTier = $pricetierNewValue;
            $newprice->disable = false;
            $newprice->save();
            return [
                "RequestSuccess" => true,
                "msg" => "New pricetier was added successfully !",
                "List" => $pricetier
            ];
        }
        else{
            return [
                "RequestSuccess" => false,
                "msg" => "A pricetier already exists. Please try with another one !",
                "List" => $pricetier
            ];
        }
    }


    ///CourseByMoneyType
    public function getListMoneytype(Request $request){
        $listMoneytype = DB::table("moneytype")
        ->get();
        return [
            'RequestSuccess' => true,
            'list' => $listMoneytype
        ];
    }
    public function getListCoursebyMoneytype(Request $request){
        $moneytype = $request->moneytype;
    
        $listCourse = DB::table("instructor_course")
        ->join("moneytype","instructor_course.moneyType_id","=","moneytype.moneyType_id")
        ->join("user","instructor_course.user_id","=","user.user_id")
        ->where("moneytype.moneyType","=",$moneytype)
        ->select("instructor_course.name as courseName","user.name","moneytype.moneyType")
        ->get();
        return [
            'RequestSuccess' => true,
            'list' => $listCourse
        ];
    }
    public function deleteMoneytype(Request $request){
        $moneyType_id = $request->moneyType_id;
        $disable = $request->disable;
        $moneyType = MoneyType::find($moneyType_id);
        if(!$moneyType){
            return [
                'RequestSuccess' => false,
                'msg' => "Not found this item !"
            ];
        }
        else{
            if($disable == false){
                $moneyType->disable = true;
                $moneyType ->save();
                return [
                    'RequestSuccess' => true,
                    'msg' => "MoneyType has been disabled !"
                ];
            }
            else{
                $moneyType->disable = false;
                $moneyType ->save();
                return [
                    'RequestSuccess' => true,
                    'msg' => "MoneyType has been enabled !"
                ];
            }
        }
    }
    public function insertMoneytype(Request $request){
        $pricetierNewValue = $request->pricetierNewValue;

        $pricetier = PriceTier::where("priceTier","=",$pricetierNewValue)->get();
   
        if(count($pricetier) == 0){
            $newprice = new PriceTier();
            $newprice->priceTier = $pricetierNewValue;
            $newprice->disable = false;
            $newprice->save();
            return [
                "RequestSuccess" => true,
                "msg" => "New pricetier was added successfully !",
                "List" => $pricetier
            ];
        }
        else{
            return [
                "RequestSuccess" => false,
                "msg" => "A pricetier already exists. Please try with another one !",
                "List" => $pricetier
            ];
        }
    }


}