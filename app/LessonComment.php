<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class LessonComment extends Model
{
    protected $table = "lesson_comment";
    protected $primaryKey = "lesson_comment_id";
    protected $fillable = ['lesson_comment_id', 'lesson_id', 'comment', 'user_id'];

    public function replies() {
        return $this->hasMany(LessonComment::class,'reply_of','lesson_comment_id')
            ->join('user','user.user_id','=','lesson_comment.user_id');
    }
}