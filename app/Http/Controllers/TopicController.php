<?php


namespace App\Http\Controllers;

use App\Admin;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TopicController
{
    function __construct()
    {
        Config::set('jwt.user', Admin::class);
        Config::set('jwt.identifier', 'admin_id');
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ]]);
    }

    public function getTopics() {
        $topics = Topic::all();
        return [
            'list' => $topics,
            'RequestSucces' =>  true
        ];
    }

    public function getTopic(Request $request) {
        $topic = Topic::find($request->category_id);
        if($topic) {
            return [
                'object' => $topic,
                'RequestSuccess' => true
            ];
        }
        return [
            'object' => null,
            'RequestSuccess' => false,
            'msg' => 'Không tìm thấy lĩnh vực'
        ];
    }

    public function insertTopic(Request $request) {
        $data = $request->all();
        $topic = new Topic($data);
        if(!DB::table('topic')->where('name',$topic->name)->first()) {
            $topic->save();
            return [
                'msg' => 'Thêm thành công',
                'RequestSuccess' => true,
                'list' => Topic::all()
            ];
        }
        return [
            'msg' => 'Tên này đã tồn tại, vui lòng nhập lại',
            'RequestSuccess' => false
        ];
    }

    public function updateTopic(Request $request) {
        $topic = Topic::find($request->topic_id);
        if($topic) {
            $topic->name = $request->name;
            $topic->icon_class= $request->icon_class;
            $topic->category_id = $request->category_id;
            $topic->save();
            return [
                'msg' => 'Sửa thành công',
                'list' => Topic::all(),
                'RequestSuccess' => true
            ];
        }
        return [
            'msg' => 'Không tìm thấy lĩnh vực',
            'RequestSuccess' => false
        ];
    }

    public function deleteTopic(Request $request) {
        $data = [
            'msg' => 'Không tìm thấy lĩnh vực',
            'RequestSuccess' => false
        ];
        $topic = Topic::find($request->topic_id);
        if($topic) {
            if($topic->disable == 0) $topic->disable = 1;
            else $topic->disable = 0;
            $topic->save();
            $data =  [
                'msg' => 'Thao tác thành công',
                'RequestSuccess' => true,
                'list' => Topic::all()
            ];
        }
        return response()->json($data,
            200,
            ['Content-type'=> 'application/json; charset=utf-8'],
            JSON_UNESCAPED_UNICODE);
    }
}