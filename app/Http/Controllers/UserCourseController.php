<?php


namespace App\Http\Controllers;

use App\InstructorCourse;
use App\Lesson;
use App\PriceTier;
use App\Topic;
use App\topic_course;
use App\WhatYouLearn;
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
            'list' => InstructorCourse::where('user_id',$user->user_id)->get()
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

}