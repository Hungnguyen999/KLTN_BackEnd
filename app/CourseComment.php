<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class CourseComment extends Model{
    protected $table = "course_comment";
    protected $primaryKey = "course_comment_id";
    protected $fillable = ['course_comment_id','course_id','comment','user_id'];
    public function InstructorCourse(){
        return $this->belongsTo('App\InstructorCourse','course_id','course_comment_id');
    }
}