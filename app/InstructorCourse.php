<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class InstructorCourse extends Model
{
    protected $table = "instructor_course";
    protected $primaryKey = "course_id";
    protected $fillable = ['name', 'description', 'user_id', 'moneyType_id', 'tierPrice_id'];

    public function topicsEnable() {
        return $this->belongsToMany(Topic::class,'topic_course','course_id','topic_id')
            ->where('disable', false);
    }
}