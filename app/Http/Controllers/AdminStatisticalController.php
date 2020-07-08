<?php
namespace App\Http\Controllers;
use App\Admin;
use App\StudentCourse;
use App\InstructorCourse;
use App\MoneyType;
use App\PriceTier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class AdminStatisticalController extends BaseController{
    function __construct(){
        Config::set('jwt.user', Admin::class);
        Config::set('jwt.identifier', 'admin_id');
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }
    public function getInfoStatistical(Request $request){
        $selectedYear = $request->year;
        $selectedMonth = $request->month;
        if($selectedYear != null && $selectedMonth != null){
            if($selectedMonth == 'Tất cả'){
                $months = [1,2,3,4,5,6,7,8,9,10,11,12];
                $listday = [];
                foreach($months as $month) {
                    $list = DB::table('student_course')
                    ->whereYear('student_course.updated_at','=',  $selectedYear)
                    ->whereMonth('student_course.updated_at','=', $month)
                    ->join('instructor_course', 'instructor_course.course_id', '=', 'student_course.course_id')
                    ->join('pricetier', 'pricetier.priceTier_id', '=', 'instructor_course.priceTier_id')
                    ->groupBy('pricetier.priceTier', 'student_course.course_id')
                    ->select(DB::raw('pricetier.priceTier as money'),DB::raw('COUNT(student_course.course_id) as count'))
                    ->get();
        
                    $monthValue = [];
                    $sum = 0;
                    foreach($list as $item) {
                        $sum += $item->money * $item->count;
                    }
        
                    array_push($monthValue, [
                        'month' => $month,
                        'sum' => $sum
                    ]);
                    
                    array_push($listday, $monthValue);
                }
                return [
                    'RequestSuccess' => true,
                    'listday' => $listday,
                ];
            }
            else{
                $date = [31,31,29,31,30,31,30,31,31,30,31,30,31];
                //ngày
                $listday = [];
                if($selectedYear % 4 == 0 && $selectedMonth == 2){
                    for($i = 1; $i < $date[$selectedMonth];$i++){
                        $getday = DB::table('student_course')
                        ->whereYear('student_course.updated_at','=', $selectedYear)
                        ->whereMonth('student_course.updated_at','=',$selectedMonth)
                        ->whereDay('student_course.updated_at','=',$i)
                        ->join('instructor_course', 'instructor_course.course_id', '=', 'student_course.course_id')
                        ->join('pricetier', 'pricetier.priceTier_id', '=', 'instructor_course.priceTier_id')
                        ->groupBy('pricetier.priceTier', 'student_course.course_id')
                        ->select(DB::raw('pricetier.priceTier as money'),DB::raw('COUNT(student_course.course_id) as count'))
                        ->get();
                
                        $dateValue = [];
                        $sum = 0;
                        foreach($getday as $item){
                            $sum += $item->money * $item->count;
                        }
                
                        array_push($dateValue, [
                            'sum' => $sum,
                            'day' => $i
                        ]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
                        array_push($listday,$dateValue);
                    }
                    return [
                        'RequestSuccess' => true,
                        'listday' => $listday
                    ];
                }
                else{
                    for($i = 1; $i <= $date[$selectedMonth];$i++){
                        $getday = DB::table('student_course')
                        ->whereYear('student_course.updated_at','=', $selectedYear)
                        ->whereMonth('student_course.updated_at','=',$selectedMonth)
                        ->whereDay('student_course.updated_at','=',$i)
                        ->join('instructor_course', 'instructor_course.course_id', '=', 'student_course.course_id')
                        ->join('pricetier', 'pricetier.priceTier_id', '=', 'instructor_course.priceTier_id')
                        ->groupBy('pricetier.priceTier', 'student_course.course_id')
                        ->select(DB::raw('pricetier.priceTier as money'),DB::raw('COUNT(student_course.course_id) as count'))
                        ->get();
                
                        $dateValue = [];
                        $sum = 0;
                        foreach($getday as $item){
                            $sum += $item->money * $item->count;
                        }
                
                        array_push($dateValue, [
                            'sum' => $sum,
                            'day' => $i
                        ]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
                        array_push($listday,$dateValue);
                    }
                    return [
                        'RequestSuccess' => true,
                        'listday' => $listday
                    ];
                }
            }
        }
        else{
            return [
                'RequestSuccess' => false,
                'msg' => "Vui lòng nhập đầy đủ thông tin để tiến hành thống kê"
            ];
        }
    }
    public function getDetailInfoStatisticalPer(Request $request){
        //List year distinct 
        $listDistinctYear = DB::table('student_course')
        ->select(DB::raw('YEAR(created_at) as year'))
        ->distinct()
        ->orderBy('year','ASC')
        ->get();
        $listYear = array();
        
        foreach($listDistinctYear as $year){
            array_push($listYear,$year->year);
        }

        $totalYear = [];
        foreach($listYear as $year){
            $listyear = DB::table('student_course')
            ->whereYear('student_course.updated_at','=', $year)
            ->join('instructor_course', 'instructor_course.course_id', '=', 'student_course.course_id')
            ->join('pricetier', 'pricetier.priceTier_id', '=', 'instructor_course.priceTier_id')
            ->groupBy('pricetier.priceTier', 'student_course.course_id')
            ->select(DB::raw('pricetier.priceTier as money'),DB::raw('COUNT(student_course.course_id) as count'))
            ->get();

            $yearValue = [];
            $sum = 0;
            foreach($listyear as $item){
                $sum += $item->money * $item->count;
            }

            array_push($yearValue,[
                'year' => $year,
                'sum' => $sum
            ]);
            array_push($totalYear,$yearValue);
        }

        return [
            //'$list' => $total,
            //'getday' => $listday,
            //'reve' => $revenueDay,
            'RequestSuccess' => true,
            'listYear' => $listYear,
            'totalYear' => $totalYear

        ];  
    }
    public function getInfoCourseSatistical(){
        $array = []; 
        $amountFreeCourse = DB::table('student_course')
        ->where('instructor_course.priceTier_id','=',0)
        ->join('instructor_course','instructor_course.course_id','=','student_course.course_id')
        ->distinct()
        ->select(DB::raw('COUNT(student_course.course_id) as value'))
        ->get();
        array_push($array,$amountFreeCourse);

        $amountPaidCourse = DB::table('student_course')
        ->join('instructor_course','instructor_course.course_id','=','student_course.course_id')
        ->distinct()
        ->where('instructor_course.priceTier_id','!=',0)
        ->select(DB::raw('COUNT(student_course.course_id) as value'))
        ->get();
        array_push($array,$amountPaidCourse);

        return [
            'RequestSuccess' => true,
            'array' => $array
        ];
    }
}