<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class AnswerBot extends Model
{
    protected $table = "answer_bot";
    protected $primaryKey = "answer_id";

    protected $fillable = ["answer_id", "answer", "question_id"];

//    public function questions() {
//        return $this->hasMany(QuestionBot::class,"answer_id","answer_id");
//    }

    public function question() {
        return $this->belongsTo(QuestionBot::class, "question_id", "question_id");
    }


}