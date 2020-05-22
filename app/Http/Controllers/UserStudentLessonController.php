<?php


namespace App\Http\Controllers;

use App\InstructorCourse;
use App\LessonComment;
use App\StudentCourse;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class UserStudentLessonController extends BaseController
{
    public function getLesson(Request $request) {
        $user = $request->user;
        $stdCourse = StudentCourse::where('user_id', $user->user_id)->where('course_id', $request->course_id)->first();
        if($stdCourse) {
            $insCourse = InstructorCourse::with('topicsEnable', 'whatYouLearn', 'instructor','lessons')
                ->find($request->course_id);
            $totalTime = 0;
            $config = [
                'ffmpeg.binaries' => './ffmpeg/bin/ffmpeg.exe',
                'ffprobe.binaries' => './ffmpeg/bin/ffprobe.exe',
                'timeout' => 3600, // The timeout for the underlying process
                'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
            ];
            $ffprobe = FFProbe::create($config);
            foreach ($insCourse->lessons as $lesson) {
                $base_video_url = "https://localhost/KLTN-Server/public/uploads/videos".'/'
                    .$stdCourse->course_id.'/'.$lesson->lesson_id.'.mp4';
                $duration = $ffprobe
                    ->format($base_video_url)
                    ->get('duration');
                $totalTime += $duration;
                $lesson->duration = gmdate('H:i:s', $duration);;
            }

            $insCourse->totalTime = gmdate('H:i:s', $totalTime);
            $insCourse->totalStudent = DB::table('student_course')
                                        ->where('course_id', $stdCourse->course_id)->count();
            $insCourse->totalLike = DB::table('course_like')->where('course_id', $stdCourse->course_id)->count();
            return [
                'list' => $insCourse,
                'RequestSuccess' => true
            ];
        }
        return [
            'msg' => '404',
            'RequestSuccess' => false
        ];
    }


    public function getComments(Request $request) {
        $user = $request->user;
        $comments = LessonComment::with('replies')
            ->where('lesson_id', $request->lesson_id)
            ->where('reply_of', '=', null)
            ->join('user','user.user_id','=','lesson_comment.user_id')->get();
        if($comments->count() > 0) {
            return [
                'list' => $comments,
                'RequestSuccess' => true
            ];
        }
        return [
            'RequestSuccess' => false
        ];
    }

    public function insertComment(Request $request) {
        $user = $request->user;
        $stdCourse = StudentCourse::where('course_id',$request->course_id)->where('user_id', $user->user_id)->first();
        if($stdCourse) {
            $reply_of = $request->reply_of;
            $cmt = new LessonComment();
            $cmt->comment = $request->comment;
            $cmt->lesson_id = $request->lesson_id;
            $cmt->user_id = $user->user_id;
            if($reply_of != null) {
                $cmt->reply_of = $reply_of;
            }
            $cmt->save();

            $comments = LessonComment::with('replies')
                ->where('lesson_id', $request->lesson_id)
                ->where('reply_of', '=', null)
                ->join('user','user.user_id','=','lesson_comment.user_id')->get();
            return [
                'list' => $comments,
                'RequestSuccess' => true
            ];
        } else {
            return [
                'RequestSuccess' => false,
                'msg' => 'Lỗi xác thực'
            ];
        }
    }

    public function deleteComment(Request $request) {
        $user = $request->user;
        $cmt = DB::table('lesson_comment')->where('user_id', $user->user_id)
            ->where('lesson_comment_id', $request->lesson_comment_id)
            ->where('lesson_id', $request->lesson_id)
            ->first();
        if($cmt) {
            DB::table('lesson_comment')->where('user_id', $user->user_id)
                ->where('lesson_comment_id', $request->lesson_comment_id)
                ->where('lesson_id', $request->lesson_id)->delete();
            $comments = LessonComment::with('replies')
                ->where('lesson_id', $request->lesson_id)
                ->where('reply_of', '=', null)
                ->join('user','user.user_id','=','lesson_comment.user_id')->get();
            return [
                'list' => $comments,
                'RequestSuccess' => true
            ];
        } else {
            return [
                'RequestSuccess' => false,
                'msg' => 'Lỗi xác thực'
            ];
        }

    }

}