<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class UserCourseController extends BaseController
{
    public function insertCourse(Request $request) {
        $path = Storage::disk('public_uploads')->putFileAs('images', $request->file('image'), 'viasdsdnh.png');
        $path1 = Storage::disk('public_uploads')->putFileAs('videos', $request->file('video'), 'vinhmp4x2.mp4');
        return 'ss';
    }
}