<?php


namespace App\Http\Controllers;

use App\Category;
use App\CourseComment;
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
                ->join('user','user.user_id','=','instructor_course.user_id')
                ->where('category.category_id','=',$category->category_id)
                ->where('instructor_course.public','=',1)
                ->where('instructor_course.disable','=',0)
                ->select( 'instructor_course.course_id','user.name as author'
                    ,'description','instructor_course.name', 'instructor_course.updated_at',DB::raw("count('student_course.course_id') as CourseCount"))
                ->orderBy('CourseCount', 'desc')
                ->distinct()
                ->groupBy('instructor_course.updated_at','category.category_id', 'user.name','description','topic.topic_id','instructor_course.course_id', 'instructor_course.name','student_course.course_id')
                ->take(10)
                ->get();
            foreach ($courseTopList as $course) {
                $wl = DB::table('what_learn_instructor_course')->where('course_id','=',$course->course_id)->get();
                $lessonList = DB::table('lesson')->where('course_id','=', $course->course_id)->get();
                $totalTime = 0;
                //   /app/vendor/ffmpeg_bundle/ffmpeg/bin/ffmpeg
                //   /app/vendor/ffmpeg_bundle/ffmpeg/bin/ffprobe
                // ahihi
                $config = [
                    'ffmpeg.binaries' => 'C:/ffmpeg/bin/ffmpeg.exe',
                    'ffprobe.binaries' => 'C:/ffmpeg/bin/ffprobe.exe',
                    'timeout' => 3600, // The timeout for the underlying process
                    'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
                ];
                $ffprobe = FFProbe::create($config);
                foreach ($lessonList as $lesson) {
                    $base_video_url = "https://localhost/KLTN-Server/public/uploads/videos".'/'
                        .$course->course_id.'/'.$lesson->lesson_id.'.mp4';
                    $totalTime +=
                        $ffprobe->format($base_video_url)->get('duration');
                }
                $priceTier = DB::table('instructor_course')
                    ->join('pricetier','pricetier.priceTier_id','=','instructor_course.priceTier_id')
                    ->where('instructor_course.course_id','=',$course->course_id)
                    ->select('priceTier')
                    ->first();
                $courseComment = CourseComment::where('course_id', $course->course_id)
                    ->select(DB::raw("COUNT('*') as COUNT"))
                    ->first();
                $topicEnable = DB::table('topic_course')
                        ->where('course_id', $course->course_id)
                        ->join('topic', 'topic.topic_id', '=','topic_course.topic_id')
                        ->where('topic.disable', false)
                        ->select('topic.topic_id','topic.name')
                        ->get();

                $course->topicEnable = $topicEnable;
                $course->totalVideo = $lessonList->count();
                $course->totalTime = gmdate('H:i:s', $totalTime);
                $course->priceTier = $priceTier->priceTier;
                $course->whatLearn = $wl;
                $course->commentCount = $courseComment->COUNT;
                if($courseComment->COUNT > 0)
                    $course->rating = $courseComment->sum('rating_value') / $courseComment->COUNT;
                else
                    $course->rating = 0;
            }
            $category->topCourseList = $courseTopList;
        }
        return [
            'list' => $categoryList
        ];
    }
}