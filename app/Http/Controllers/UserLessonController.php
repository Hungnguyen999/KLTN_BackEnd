<?php


namespace App\Http\Controllers;

use App\Lesson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class UserLessonController extends BaseController
{
    public function getLessons(Request $request) {
            $user = $request->user;
            $list = DB::table('lesson')
                ->join('instructor_course','lesson.course_id','=','instructor_course.course_id')
                ->where('instructor_course.user_id','=',$user->user_id)
                ->where('lesson.course_id','=', $request->course_id)
                ->select('lesson.lesson_id','lesson.title', 'lesson.description', 'lesson.course_id','lesson.updated_at')->get();
            return [
                'list' => $list
            ];
    }
}