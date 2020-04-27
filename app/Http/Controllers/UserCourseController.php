<?php


namespace App\Http\Controllers;

use App\InstructorCourse;
use App\Lesson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
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
        /*
         * $count = $request->lesson_count;
        for($i = 0; $i< $count; $i++) {
            $data = [
                'title' => $request->input('lesson_title_'.$i),
                'description' => $request->input('lesson_descriptipn_'.$i),
                'course_id' => $course->course_id
            ];
            $lesson = new Lesson($data);
            $lesson->save();

            insertCourse
        }
         * */
        return [
            'RequestSuccess' => true,
            'msg' => 'Tạo khóa học thành công',
            'list' => InstructorCourse::where('user_id',$user->user_id)->get()
        ];
    }

    public function getCourses(Request $request) {
        $user = $request->user;
        return ['list' => InstructorCourse::where('user_id', $user->user_id)->get()];
    }

}