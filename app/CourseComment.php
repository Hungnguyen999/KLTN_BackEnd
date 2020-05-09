<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class CourseComment extends Model{
    protected $table = "course_comment";
    protected $primaryKey = "course_comment_id";
    protected $fillable = ['course_comment_id','course_id','comment','user_id'];
    public function author(){
        return $this->belongsTo(User::class,'user_id','user_id');
    }
}