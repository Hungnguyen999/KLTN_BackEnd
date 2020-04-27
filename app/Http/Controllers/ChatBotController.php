<?php


namespace App\Http\Controllers;
use App\Admin;
use App\AnswerBot;
use App\MessageBot;
use App\MessageQuestionBot;
use App\QuestionBot;
use App\TypeQuestionBot;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Psr7\_parse_request_uri;


class ChatBotController extends BaseController
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

    public function getQuestionBots() {
        return [
            'list' => QuestionBot::with("answers","type")->get()
        ];
    }

    public function insertQuestionBot(Request $request) {
        $find = QuestionBot::where("question", $request->question)->first();
        if(!$find) {
            $qs = new QuestionBot($request->all());
            $qs->save();

            $answerList = json_decode($request->answerList);
            $msg = "Thêm tin nhắn thành công";
            foreach ($answerList as $answer) {
                $find = AnswerBot::where('answer', '=', $answer->answer)
                    ->where('question_id', $qs->question_id)->first();
                if(!$find) {
                    $newAnswer = new AnswerBot();
                    $newAnswer->answer = $answer->answer;
                    $newAnswer->question_id = $qs->question_id;
                    $newAnswer->save();
                } else {
                    $msg = "Câu trả lời trùng sẽ không được thêm";
                }
            }
            return [
                'RequestSuccess' => true,
                'msg' => $msg,
                'list' => QuestionBot::with("answers")->get()
            ];
        }
        return [
            'RequestSuccess' => false,
            'msg' => 'Câu hỏi này đã tồn tại'
        ];
    }

    public function updateQuestionBot(Request $request) {
        $qs = QuestionBot::find($request->question_id);
        $find = QuestionBot::where('question','=',$request->question)->where('question_id','<>',$request->question_id)->first();
        if($qs && !$find) {
            $qs->question = $request->question;
            $qs->save();
            return [
                'msg' => 'Cập nhập thành công',
                'RequestSuccess' => true,
                'list' =>  QuestionBot::with("answers")->get()
            ];
        }
        if($find) {
            return [
                'msg' => 'Tin nhắn này đã tồn tại!',
                'RequestSuccess' => false
            ];
        }
        return [
            'msg' => 'Không tìm thấy tin nhắn!',
            'RequestSuccess' => false
        ];
    }

    public function deleteQuestionBot(Request $request) {
        $qs = DB::table('question_bot')->where('question_id', $request->question_id)->get();
        if($qs) {
            DB::table('answer_bot')->where('question_id',$request->question_id)->delete();
            DB::table('question_bot')->where('question_id', $request->question_id)->delete();
            return [
                'msg' => 'Xóa thành công',
                'RequestSuccess' => true,
                'list' =>  QuestionBot::with("answers")->get()
            ];
        }
        return [
            'msg' => 'Không tìm thấy tin nhắn!',
            'RequestSuccess' => false
        ];
    }

    public function insertAnswerBot(Request $request) {
        $find = AnswerBot::where('answer', '=', $request->answer)
            ->where('question_id', $request->question_id)->first();
        $findQuestion = QuestionBot::find($request->question_id);
        if(!$find && $findQuestion) {
            $aws = new AnswerBot($request->all());
            $aws->save();
            return [
                'msg' => 'Thêm thành công',
                'RequestSuccess' => true,
                'list' => QuestionBot::with("answers")->get()
            ];
        }
        if(!$findQuestion) {
            return [
                'msg' => 'Không tìm tin nhắn phản hồi!',
                'RequestSuccess' => false
            ];
        }
        return [
            'msg' => 'Câu trả lời này đã tồn tại!',
            'RequestSuccess' => false
        ];
    }

    public function updateAnswerBot(Request $request) {
        $aws = AnswerBot::find($request->answer_id);
        $find = AnswerBot::where('answer','=',$request->answer)
            ->where('answer_id','<>',$request->answer_id)
            ->where('question_id', $request->question_id)->first();
        if($aws && !$find) {
            $aws->answer = $request->answer;
            $aws->save();
            return [
                'msg' => 'Cập nhập thành công',
                'RequestSuccess' => true,
                'list' =>  QuestionBot::with("answers")->get()
            ];
        }
        if($find) {
            return [
                'msg' => 'Câu trả lời này đã tồn tại!',
                'RequestSuccess' => false
            ];
        }
        return [
            'msg' => 'Không tìm thấy câu trả lời!',
            'RequestSuccess' => false
        ];
    }

    public function deleteAnswerBot(Request $request) {
        $aws = AnswerBot::find($request->answer_id);
        if($aws) {
            DB::table('answer_bot')->where('answer_id', '=',$request->answer_id)->delete();
            return [
                'msg' => 'Xóa thành công',
                'RequestSuccess' => true,
                'list' =>  QuestionBot::with("answers")->get()
            ];
        }
        return [
            'msg' => 'Không tìm thấy câu trả lời!',
            'RequestSuccess' => false
        ];
    }

    public function getMessageBots() {
        $msgList = QuestionBot::where('type_question_bot_id', 1)->get();
        foreach ($msgList as $msg) {
            $msg->message_bot_id = $msg->question_id;
            $msg->name = $msg->question;
            $msg->questions = [];
            $action_questions = DB::table("message_bot")
                    ->join('question_bot','question_bot.question_id','=','message_bot.action_question_id')
                   ->where('message_bot.question_id', $msg->question_id)
                    ->select('question_bot.question_id','question_bot.question', 'question_bot.type_question_bot_id','question_bot.created_at', 'question_bot.updated_at')
                    ->get();
            foreach ($action_questions as $action_question) {
                $type = TypeQuestionBot::find($action_question->type_question_bot_id);
                $action_question->type = $type;
            }
            $msg->questions = $action_questions;
        }
        return [
            'list' => $msgList
        ];
    }

    function summaryMessageBots() {
        $msgList = QuestionBot::where('type_question_bot_id', 1)->get();
        foreach ($msgList as $msg) {
            $msg->message_bot_id = $msg->question_id;
            $msg->name = $msg->question;
            $msg->questions = [];
            $action_questions = DB::table("message_bot")
                ->join('question_bot','question_bot.question_id','=','message_bot.action_question_id')
                ->where('message_bot.question_id', $msg->question_id)
                ->select('question_bot.question_id','question_bot.question', 'question_bot.type_question_bot_id','question_bot.created_at', 'question_bot.updated_at')
                ->get();
            foreach ($action_questions as $action_question) {
                $type = TypeQuestionBot::find($action_question->type_question_bot_id);
                $action_question->type = $type;
            }
            $msg->questions = $action_questions;
        }
        return $msgList;
    }

    public function insertMessageBot(Request $request) {
        $qs = new QuestionBot($request->all());
        $find = QuestionBot::where('question',$request->name)->first();
        if(!$find) {
            $qs->question = $request->name;
            $qs->type_question_bot_id = 1;
            $qs->save();
            $questionList = json_decode($request->questionList);
            foreach ($questionList as $question) {
                $msg_bot = new MessageBot();
                $msg_bot->question_id = $qs->question_id;;
                $msg_bot->action_question_id = $question->question_id;
                $msg_bot->save();
            }
            return [
                'msg' => 'Tạo tin nhắn thành công',
                'RequestSuccess' => true,
                "list" => $this->summaryMessageBots()
            ];
        }
        return [
            'msg' => 'Tên tin nhắn đã tồn tại',
            'RequestSuccess' => false
        ];
    }

    public function updateMessageBot(Request $request) {
        $find = QuestionBot::where('question',$request->name)->where('question_id', '<>', $request->message_bot_id)->first();
        $qs = QuestionBot::find($request->message_bot_id);
        if($qs && !$find) {
            $qs->question = $request->name;
            $qs->save();
            $questionList = json_decode($request->questionList);
            DB::table("message_bot")->where("question_id",$qs->question_id)->delete();
            foreach ($questionList as $question) {
                $msg_bot = new MessageBot();
                $msg_bot->question_id = $qs->question_id;;
                $msg_bot->action_question_id = $question->question_id;
                $msg_bot->save();
            }
            return [
                'msg' => 'Cập nhật tin nhắn thành công',
                'RequestSuccess' => true,
                "list" => $this->summaryMessageBots()
            ];
        }
        if($find) {
            return [
                'msg' => 'Tên tin nhắn này đã tồn tại',
                'RequestSuccess' => false
            ];
        }
        return [
            'msg' => 'Không tìm thấy tin nhắn',
            'RequestSuccess' => false
        ];
    }

    public function deleteMessageBot(Request $request) {
        $qs = QuestionBot::find($request->message_bot_id);
        if($qs) {
            DB::table('message_bot')->where('question_id', $request->message_bot_id)->delete();
            DB::table('question_bot')->where('question_id', $request->message_bot_id)->delete();
            return [
                'msg' => 'Xóa thành công',
                'RequestSuccess' => true,
                'list' => $this->summaryMessageBots()
            ];
        }
        return [
            'msg' => 'Không tìm thấy tin nhắn',
            'RequestSuccess' => false
        ];
    }
}