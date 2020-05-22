<?php


namespace App\Http\Controllers;
use App\Category;
use App\InstructorCourse;
use App\PriceTier;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class GuestSearchController extends BaseController
{
    public function getPriceTier() {
        return [
            'list' => PriceTier::all()
        ];
    }

    public function getItemsSearch(Request $request) {
        $courses = DB::table('instructor_course')
            ->join('pricetier', 'pricetier.priceTier_id', '=','instructor_course.priceTier_id')
            ->select('instructor_course.course_id',
                "instructor_course.name",'pricetier.priceTier', 'instructor_course.updated_at')->get();
        foreach ($courses as $course) {
            $tempCount = DB::table('student_course')
                ->where('course_id', $course->course_id)->select(DB::raw('COUNT("user_id") as count'))->first();
            $tempStar = DB::table('course_comment')
                ->where('course_id', $course->course_id)
                ->select('rating_value')->get();

            $lessonList = DB::table('lesson')->where('course_id','=', $course->course_id)->get();
            $totalTime = 0;
            $config = [
                'ffmpeg.binaries' => './ffmpeg/bin/ffmpeg.exe',
                'ffprobe.binaries' => './ffmpeg/bin/ffprobe.exe',
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

            $course->studentCount = $tempCount->count;
            if($tempStar == null || $tempStar->count() == 0)
                $course->star = 0;
            else
                $course->star = $tempStar->sum('rating_value') / $tempStar->count();


            $course->totalVideo = $lessonList->count();
            $course->totalTime = gmdate('H:i:s', $totalTime);

            $tempCourse = InstructorCourse::with('topicsEnable.category')->find($course->course_id);

            $course->topic = $tempCourse->topicsEnable;
        }
        return [
            'list' => $courses
        ];
    }
}