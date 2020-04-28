<?php


namespace App\Http\Controllers;
use App\InstructorCourse;
use App\CourseComment;
use App\User;
use App\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class GuestDetailController extends BaseController
{
    function __construct(){
        Config::set('jwt.user',User::class);
        Config::set('jwt.indentifier','user_id');
        Config::set('auth.providers',['users'=>[
            'driver'=> 'eloquent',
            'model'=>User::class,
        ]]);
    }
    public function getDetailCourse(Request $request){
        $course_id = $request->course_id;
        $detailCourse = DB::table('instructor_course')
            ->where("instructor_course.course_id",$course_id)
            ->first();


        $amountReview = DB::table('course_comment')
            ->where("course_comment.course_id","=",$course_id)
            ->count(DB::raw('course_comment.course_id'));
        if($detailCourse)
        {
            return [
                'RequestSuccess' => true,
                'detail' => $detailCourse,
                'amounReview' => $amountReview,
            ];
        }
        else{
            return [
                'RequestSuccess' => false,
                'msg' => "Không tìm thấy khóa học"
            ];
        }
    }
    public function getInfoInstructor(Request $request){
        $course_id = 1;
        $user_id = DB::table('user')
            ->join("instructor_course","user.user_id","=","instructor_course.user_id")
            ->where("instructor_course.course_id",'=',$course_id)
            ->select("instructor_course.user_id")
            ->first();

        $totalCourse = DB::table('instructor_course')
            ->where("instructor_course.user_id",'=',json_decode( json_encode($user_id)), true)
            ->count(DB::raw('user_id'));

        $infoInstructor = DB::table('user')
            ->join("instructor_course","user.user_id","=","instructor_course.user_id")
            ->where("instructor_course.user_id",'=',json_decode( json_encode($user_id)), true)
            ->select("user.user_id","user.address","user.name","user.profile")
            ->first();

        return [
            'RequestSuccess' => true,
            'info' => $user_id,
            'total' => $totalCourse,
            'infoInstructor' => $infoInstructor
        ];
    }
    public function getTop5CourseByTopic(Request $request){
        $course_id = 1;
        $topic_id = DB::table('instructor_course')
            ->join('topic_course','instructor_course.course_id','=','topic_course.course_id')
            ->where('instructor_course.course_id','=',$course_id)
            ->select('topic_course.topic_id')
            ->get();

        $real = json_decode( json_encode($topic_id), true);

        //Lấy top 5 course có nhiều người đăng ký nhất
        $topfive = DB::table('student_course')
            ->join("instructor_course","student_course.course_id","=","instructor_course.course_id")
            ->join('topic_course','instructor_course.course_id','=','topic_course.course_id')
            ->where('topic_course.topic_id','=', $real)
            ->orderBy("course_count","desc")
            ->groupBy("instructor_course.course_id","instructor_course.name","instructor_course.user_id")
            ->select('instructor_course.course_id','instructor_course.name','instructor_course.user_id',DB::raw("COUNT('course_id') AS course_count"))
            ->take(2)
            ->get();

        $count = $topfive->count();
        return [
            'RequestSuccess' => true,
            'list' => $topic_id,
            'topfive' => $topfive,
            'count' =>  $count,
        ];

    }
    public function insertComment(Request $request){
        $user = $request->user;

        $comment = new CourseComment($request->all());
        $user_id = $user->user_id;
        $course_id = $request->course_id;
        $text_comment = $request->textComment;
        $ratingValue = $request->ratingValue;

        $comment->user_id = $user_id;
        $comment->comment = $text_comment;
        $comment->course_id = $course_id;
        $comment->rating_value = $ratingValue;
        $comment->save();
        return [
            'msg'=>"Đã thêm comment"
        ];
    }
    public function getListComment(Request $request){
        $listComment = DB::table('course_comment')->where('course_id',$request->course_id)->get();
        return [
            'RequestSuccess' => true,
            'list' => $listComment
        ];
    }
}
