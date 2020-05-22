<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class InstructorCourse extends Model
{
    protected $table = "instructor_course";
    protected $primaryKey = "course_id";
    protected $fillable = ['name', 'description', 'user_id', 'moneyType_id', 'priceTier_id'];

    public function topicsEnable() {
        return $this->belongsToMany(Topic::class,'topic_course','course_id','topic_id')
            ->where('disable', false);
    }

    public function priceTier() {
        return $this->belongsTo(PriceTier::class,'priceTier_id','priceTier_id');
    }

    public function whatYouLearn() {
        return $this->hasMany(WhatYouLearn::class, 'course_id', 'course_id');
    }

    public function instructor() {
        return $this->belongsTo(User::class, 'user_id','user_id');
    }

    public function course_comment() {
        return $this->hasMany(CourseComment::class, 'course_id', 'course_id');
    }

    public function lessons() {
        return $this->hasMany(Lesson::class,'course_id', 'course_id');
    }

}