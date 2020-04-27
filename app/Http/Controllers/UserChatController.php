<?php


namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class UserChatController extends BaseController
{
    public function message(Request $request) {
        $user = $request->user;
        $message = new Message($request->all());
        $message->fromUser = $user->user_id;
        $message->save();
    }
    public function getMessage(Request $request) {
        return (Message::with('from','to')->get());
    }

    public function getMyInstructors(Request $request) {
        $user = $request->user;
        $list = DB::table('student_course')
            ->join('instructor_course','instructor_course.course_id','=','student_course.course_id')
            ->join('user','user.user_id','=','instructor_course.user_id')
            ->where('student_course.user_id', '=', $user->user_id)
            ->select('user.user_id','user.name')
            ->get();
        return $list;
    }
}