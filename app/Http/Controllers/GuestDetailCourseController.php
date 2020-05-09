<?php


namespace App\Http\Controllers;
use App\InstructorCourse;
use App\CourseComment;
use App\User;
use App\StudentCourse;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class GuestDetailCourseController extends BaseController
{
    function __construct(){
        Config::set('jwt.user',User::class);
        Config::set('jwt.indentifier','user_id');
        Config::set('auth.providers',['users'=>[
            'driver'=> 'eloquent',
            'model'=>User::class,
        ]]);
    }
//    public function getDetailCourse(Request $request){
//        $course_id = $request->course_id;
//        $detailCourse = DB::table('instructor_course')
//        ->where("instructor_course.course_id",$course_id)
//        ->first();
//
//
//        $amountReview = DB::table('course_comment')
//        ->where("course_comment.course_id","=",$course_id)
//        ->count(DB::raw('course_comment.course_id'));
//        if($detailCourse)
//        {
//            return [
//                'RequestSuccess' => true,
//                'detail' => $detailCourse,
//                'amounReview' => $amountReview,
//            ];
//        }
//        else{
//            return [
//                'RequestSuccess' => false,
//                'msg' => "Không tìm thấy khóa học"
//            ];
//        }
//    }
//    public function getInfoInstructor(Request $request){
//        $course_id = 1;
//        $user_id = DB::table('user')
//        ->join("instructor_course","user.user_id","=","instructor_course.user_id")
//        ->where("instructor_course.course_id",'=',$course_id)
//        ->select("instructor_course.user_id")
//        ->first();
//
//        $real = json_decode( json_encode($user_id));
//
//        $totalCourse = DB::table('instructor_course')
//        ->where("instructor_course.user_id",'=', $real , true)
//        ->count(DB::raw('user_id'));
//
//        $infoInstructor = DB::table('user')
//        ->join("instructor_course","user.user_id","=","instructor_course.user_id")
//        ->where("instructor_course.user_id",'=',$real, true)
//        ->select("user.user_id","user.address","user.name","user.profile")
//        ->first();
//
//        return [
//            'RequestSuccess' => true,
//            'info' => $user_id,
//            'total' => $totalCourse,
//            'infoInstructor' => $infoInstructor
//        ];
//    }
//    public function getTop5CourseByTopic(Request $request){
//        $course_id = 1;
//        $topic_id = DB::table('instructor_course')
//        ->join('topic_course','instructor_course.course_id','=','topic_course.course_id')
//        ->where('instructor_course.course_id','=',$course_id)
//        ->select('topic_course.topic_id')
//        ->get();
//
//        $real = json_decode( json_encode($topic_id), true);
//
//        //Lấy top 5 course có nhiều người đăng ký nhất
//        $topfive = DB::table('student_course')
//        ->join("instructor_course","student_course.course_id","=","instructor_course.course_id")
//        ->join('topic_course','instructor_course.course_id','=','topic_course.course_id')
//        ->where('topic_course.topic_id','=', $real)
//        ->orderBy("course_count","desc")
//        ->groupBy("instructor_course.course_id","instructor_course.name","instructor_course.user_id")
//        ->select('instructor_course.course_id','instructor_course.name','instructor_course.user_id',DB::raw("COUNT('course_id') AS course_count"))
//        ->take(2)
//        ->get();
//
//        $count = $topfive->count();
//        return [
//            'RequestSuccess' => true,
//            'list' => $topic_id,
//            'topfive' => $topfive,
//            'count' =>  $count,
//        ];
//
//    }





    public function getDetailCourse(Request $request) {
        $course = InstructorCourse
            ::with('whatYouLearn', 'priceTier', 'instructor.ins_courses.course_comment', 'topicsEnable', 'course_comment.author')
            ->where('course_id', $request->course_id)
            ->where('disable', 0)
            ->where('public', 1)
            ->first();
        if($course) {
            $topics = DB::table('topic_course')
                        ->where('course_id', $course->course_id)->get();
            $ArrayCourse = [];
            foreach ($topics as $topic) {
                $courses = DB::table('topic_course')
                                ->where('topic_course.topic_id', $topic->topic_id)
                                ->join('instructor_course','instructor_course.course_id', '=','topic_course.course_id')
                                ->join('course_like','course_like.course_id','=','instructor_course.course_id')
                                ->join('pricetier', 'pricetier.priceTier_id', '=','instructor_course.priceTier_id')
                                ->groupBy('course_like.course_id', "instructor_course.name", 'pricetier.priceTier','instructor_course.updated_at')
                                ->orderBy(DB::raw("COUNT(course_like.user_id)"),'desc')
                                ->where('instructor_course.course_id','<>',$course->course_id)
                                ->select('course_like.course_id', DB::raw("COUNT(course_like.user_id) as likeCount"),
                                    "instructor_course.name",'pricetier.priceTier', 'instructor_course.updated_at')->get();
                foreach ($courses as $cs) {
                    $flag = true;
                    foreach ($ArrayCourse as $myCourse) {
                        if($cs->course_id == $myCourse->course_id || $cs->course_id == $course->course_id) {
                            $flag = false;
                            break;
                        }
                    }
                    if($flag) {
                        $tempCount = DB::table('student_course')
                            ->where('course_id', $cs->course_id)->select(DB::raw('COUNT("user_id") as count'))->first();
                        $tempStar = DB::table('course_comment')
                            ->where('course_id', $cs->course_id)
                            ->select('rating_value')->get();

                        $lessonList = DB::table('lesson')->where('course_id','=', $cs->course_id)->get();
                        $totalTime = 0;
                        $config = [
                            'ffmpeg.binaries' => 'ffmpeg/bin/ffmpeg.exe',
                            'ffprobe.binaries' => 'ffmpeg/bin/ffprobe.exe',
                            'timeout' => 3600, // The timeout for the underlying process
                            'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
                        ];
                        $ffprobe = FFProbe::create($config);
                        foreach ($lessonList as $lesson) {
                            $base_video_url = "https://localhost/KLTN-Server/public/uploads/videos".'/'
                                .$cs->course_id.'/'.$lesson->lesson_id.'.mp4';
                            $totalTime += $ffprobe
                                ->format($base_video_url)
                                ->get('duration');
                        }

                        $cs->studentCount = $tempCount->count;
                        if($tempStar == null || $tempStar->count() == 0)
                            $cs->star = 0;
                        else
                            $cs->star = $tempStar->sum('rating_value') / $tempStar->count();
                        $cs->totalVideo = $lessonList->count();
                        $cs->totalTime = gmdate('H:i:s', $totalTime);
                        array_push($ArrayCourse, $cs);
                    }
                }
            }
            $temp = collect($ArrayCourse)->sortByDesc('likeCount');
            $course->top5 = json_decode($temp->take(5));
            $course->whatLearn =$course->whatYouLearn($course);
            $course->priceTier = $course->priceTier($course);
            return [
                'object' => $course,
                'RequestSuccess' => true
            ];
        }
        return [
            'msg' => 'Không tìm thấy khóa học',
            'RequestSuccess' => false
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
