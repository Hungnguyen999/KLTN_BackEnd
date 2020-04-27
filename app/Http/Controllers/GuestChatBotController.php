<?php


namespace App\Http\Controllers;

use App\MessageBot;
use App\QuestionBot;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;

class GuestChatBotController extends BaseController
{
    public function chatBot(Request $request) {
        $qs = QuestionBot::where("question", $request->question)->first();
        if($qs) {
            if($qs->type_question_bot_id == 1) {
                $msgBotList = DB::table("message_bot")
                    ->where('message_bot.question_id', $qs->question_id)
                    ->join('question_bot', 'question_bot.question_id','=','message_bot.action_question_id')
                    ->select('question_bot.question')
                    ->get();

                return [
                    'object' => [
                        'questions' => $msgBotList,
                        'type_question_bot_id' => 1
                    ]
                ];
            } else {
                $answerBotList  = DB::table('answer_bot')
                    ->where('question_id', $qs->question_id)
                    ->select('answer')
                    ->get();
                return [
                    'object' => [
                        'answers' => $answerBotList,
                        'type_question_bot_id' => 2
                    ]
                ];
            }
        }
        $answer = ['answer' => 'Xin lỗi, BOT không hiểu ý bạn'];
        $answers = array($answer);
        return [
            'object' => [
                'answers' => $answers,
                'type_question_bot_id' => 2
            ]
        ];
    }
}