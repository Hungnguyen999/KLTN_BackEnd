<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class QuestionBot extends Model
{
    protected $table = "question_bot";
    protected $primaryKey = "question_id";

    protected $fillable = ["question", "question_id", "type_question_bot_id"];

//    public function getAnswer() {
//        return $this->belongsTo(AnswerBot::class,"ans wer_id","answer_id");
//    }

    public function type() {
        return $this->belongsTo(TypeQuestionBot::class, 'type_question_bot_id','type_question_bot_id');
    }

    public function answers() {
        return $this->hasMany(AnswerBot::class, "question_id", "question_id");
    }

    public function questions() {
        return $this->hasMany(MessageBot::class, "question_id", "action_question_id");
    }


}