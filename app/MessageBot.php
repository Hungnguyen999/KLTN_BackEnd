<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class MessageBot extends Model
{
    protected $table = "message_bot";
    protected $primaryKey = "action_question_id";
    protected $fillable = ['question_id', 'action_question_id'];

    public function question() {
        return $this->hasOne(QuestionBot::class, "question_id", "action_question_id");
    }

}