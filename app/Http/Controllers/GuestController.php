<?php


namespace App\Http\Controllers;

use App\Category;
use App\Lesson;
use FFMpeg\FFProbe;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class GuestController extends BaseController
{
    public function getCategory() {
        return [
            'list' => Category::with('topicsEnable','topicsEnable.courseEnable')->where('disable',false)->get()
        ];
    }

    public function getCategoryWithTopCourse() {
        $categoryList = Category::all();
        foreach ($categoryList as $category) {
            $courseTopList = DB::table('category')
                ->join('topic','topic.category_id','=','category.category_id')
                ->join('topic_course','topic_course.topic_id','=','topic.topic_id')
                ->join('instructor_course','instructor_course.course_id','=','topic_course.course_id')
                ->join('student_course','student_course.course_id', '=', 'instructor_course.course_id')
                ->where('category.category_id','=',$category->category_id)
                ->where('instructor_course.public','=',1)
                ->where('instructor_course.disable','=',0)
                ->select( 'instructor_course.course_id','instructor_course.user_id as author'
                    ,'description','instructor_course.name',DB::raw("count('student_course.course_id') as CourseCount"))
                ->orderBy('CourseCount', 'desc')
                ->groupBy('category.category_id', 'instructor_course.user_id','description','topic.topic_id','instructor_course.course_id', 'instructor_course.name','student_course.course_id')
                ->take(10)
                ->get();
            foreach ($courseTopList as $course) {
                $wl = DB::table('what_learn_instructor_course')->where('course_id','=',$course->course_id)->get();
                $lessonList = DB::table('lesson')->where('course_id','=', $course->course_id)->get();
                $totalTime = 0;
                foreach ($lessonList as $lesson) {
                    $config = [
                        'ffmpeg.binaries' => 'C:/ffmpeg/bin/ffmpeg.exe',
                        'ffprobe.binaries' => 'C:/ffmpeg/bin/ffprobe.exe',
                        'timeout' => 3600, // The timeout for the underlying process
                        'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
                    ];
                    $ffprobe = FFProbe::create($config);
                    $base_video_url = "http://localhost:8080/KLTN-Server/public/uploads/videos".'/'
                        .$course->course_id.'/'.$lesson->lesson_id.'.mp4';
                    $totalTime += $ffprobe
                        ->format($base_video_url)
                        ->get('duration');
                }
                $course->totalVideo = $lessonList->count();
                $course->totalTime = gmdate('H:i:s', $totalTime);
                $course->whatLearn = $wl;
            }
            $category->topCourseList = $courseTopList;
        }
        return [
            'list' => $categoryList
        ];
    }
}