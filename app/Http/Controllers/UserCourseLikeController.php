<?php


namespace App\Http\Controllers;

use App\CourseLike;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class UserCourseLikeController extends BaseController
{
    public function getLikeList(Request $request) {
        $user = $request->user;
        return [
            'list' => CourseLike::with('course','course.priceTier')->where('user_id', $user->user_id)->get()
        ];
    }

    public function likeOrUnlike(Request $request) {
        $user = $request->user;
        $like = DB::table('course_like')
                ->where('user_id', $user->user_id)
                ->where('course_id', $request->course_id)
                ->first();
        if($like) {
            DB::table('course_like')
                ->where('user_id', $user->user_id)
                ->where('course_id', $request->course_id)->delete();
            return [
                'list' => CourseLike::with('course','course.priceTier')->where('user_id', $user->user_id)->get()
            ];
        } else {
            $like = new CourseLike();
            $like->course_id = $request->course_id;
            $like->user_id = $user->user_id;
            $like->save();
            return [
                'list' => CourseLike::with('course','course.priceTier')->where('user_id', $user->user_id)->get()
            ];
        }
    }


}