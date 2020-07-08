<?php 
namespace App\Http\Controllers;
use App\Admin;
use App\InstructorCourse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class AdminCourseController extends BaseController{
    function __construct(){
        Config::set('jwt.user', Admin::class);
        Config::set('jwt.identifier', 'admin_id');
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }
    public function getListCourse(Request $request){
        $listCourse = DB::table('instructor_course')
        ->join('user','user.user_id','=','instructor_course.user_id')
        ->select(DB::raw('user.name as InstructorName'),'instructor_course.course_id','instructor_course.name','instructor_course.created_at','instructor_course.updated_at','instructor_course.description','instructor_course.disable','instructor_course.public')
        ->get();

        return [
            'RequestSuccess' => true,
            'list' => $listCourse
        ];
    }
    public function unactiveCourse(Request $request){
            $course_id = $request->course_id;
            $course = InstructorCourse::find($course_id);
            if(!$course){
                return [
                    'RequestSuccess' => false,
                    'msg' => "Không tìm thấy !"
                ];
            }
            else{
                $course->disable = true;
                $course->save();
                return [
                    'RequestSuccess' => true,
                    'msg' => "Đã vô hiệu hóa khóa học"
                ];
            }
    }
    public function activeCourse(Request $request){
            $course_id = $request->course_id;
            $course = InstructorCourse::find($course_id);

            if(!$course){
                return [
                    'RequestSuccess' => false,
                    'msg' => "Không tìm thấyyyy !"
                ];
            }
            else{
                $course->disable = false;
                $course->save();
                return [
                    'RequestSuccess' => true,
                    'msg' => "Đã kích hoạt khóa học"
                ];
            }
    }
}


