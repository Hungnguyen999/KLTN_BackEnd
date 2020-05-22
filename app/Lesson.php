<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = "lesson";
    protected $primaryKey = "lesson_id";
    protected $fillable = ['title', 'description', 'course_id', 'commentsCount', 'views'];

    public function comments() {
        return $this->hasMany(LessonComment::class, 'lesson_id', 'lesson_id')
            ->join('user','user.user_id','=','lesson_comment.user_id')
            ->where('reply_of', '=', null);
    }
}