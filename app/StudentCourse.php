<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentCourse extends Model
{
    protected $table = "student_course";
    protected $primaryKey = "course_id";
    public $incrementing = false;
    protected $fillable = ['user_id', 'course_id'];

}