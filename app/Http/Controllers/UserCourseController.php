<?php


namespace App\Http\Controllers;

use App\CourseComment;
use App\InstructorCourse;
use App\Lesson;
use App\PriceTier;
use App\Topic;
use App\topic_course;
use App\User;
use App\WhatYouLearn;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserCourseController extends BaseController
{
    public function insertCourse(Request $request) {

        //$path = Storage::disk('public_uploads')->putFileAs('images', $request->file('image'), 'viasdsdnh.png');
        //$path1 = Storage::disk('public_uploads')->putFileAs('videos', $request->file('video'), 'vinhmp4x2.mp4');

        $course = new InstructorCourse($request->all());
        $user = $request->user;
        $course->user_id = $user->user_id;
        $course->save();
        Storage::disk('public_uploads')->putFileAs('images/'.$course->course_id, $request->file('image'), $course->course_id.'.png');

        $topics = json_decode($request->topic_id_list);
        foreach ($topics as $topic) {
            $tp = new topic_course();
            $tp->course_id = $course->course_id;
            $tp->topic_id = $topic;
            $tp->save();
        }

        $whatYouLearn = json_decode($request->whatYouLearn);
        foreach ($whatYouLearn as $learn) {
            $what = new WhatYouLearn();
            $what->learn = $learn->text;
            $what->course_id = $course->course_id;
            $what->save();
        }


        return [
            'RequestSuccess' => true,
            'msg' => 'Tạo khóa học thành công',
            'list' => InstructorCourse::with('whatYouLearn','priceTier', 'topicsEnable')->where('user_id', $user->user_id)->get()
        ];
    }

    public function getCourses(Request $request) {
        $user = $request->user;
        return ['list' => InstructorCourse::with('whatYouLearn','priceTier', 'topicsEnable')->where('user_id', $user->user_id)->get()];
    }

    public function publicOrUnPublicCourse(Request $request) {
        $user = $request->user;
        $course = InstructorCourse::where('user_id', $user->user_id)->where('course_id', $request->course_id)->first();
        if($course) {
            $public = 0;
            if($course->public == 0) $public = 1;
            $course->public = $public;
            $course->save();
            return [
                'RequestSuccess' => true,
                'msg' => 'Cập nhập thành công'
            ];
        }
        return [
            'msg' => 'Không tìm thấy khóa học',
            'RequestSuccess' => false
        ];
    }

    public function getPriceTier() {
        return [
            'list' => PriceTier::all()
        ];
    }


    public function studentGetCourses(Request $request) {
        $user = $request->user;
        if(User::find($user->user_id)) {
            $myCourses = DB::table('student_course')
                ->join('instructor_course','instructor_course.course_id', '=', 'student_course.course_id')
                ->join('user', 'user.user_id','=','instructor_course.user_id')
                ->where('instructor_course.public','=',1)
                ->where('instructor_course.disable','=',0)
                ->where('student_course.user_id', $user->user_id)
                ->select( 'instructor_course.course_id','user.name as author'
                    ,'description','instructor_course.name', 'instructor_course.updated_at',DB::raw("count('student_course.course_id') as CourseCount"))
                ->orderBy('CourseCount', 'desc')
                ->distinct()
                ->groupBy('instructor_course.updated_at', 'user.name','description','instructor_course.course_id', 'instructor_course.name','student_course.course_id')
                ->get();
            foreach ($myCourses as $course) {
                $wl = DB::table('what_learn_instructor_course')->where('course_id','=',$course->course_id)->get();
                $lessonList = DB::table('lesson')->where('course_id','=', $course->course_id)->get();
                $totalTime = 0;
                //   /app/vendor/ffmpeg_bundle/ffmpeg/bin/ffmpeg
                //   /app/vendor/ffmpeg_bundle/ffmpeg/bin/ffprobe
                // ahihi
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
                        $ffprobe
                            ->format($base_video_url)
                            ->get('duration');
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
            return [
                'list' => $myCourses
            ];
        }
        return [
            'msg' => 'Lỗi đăng nhập',
            'RequestSuccess' => false
        ];
    }
}