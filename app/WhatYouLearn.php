<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class WhatYouLearn extends Model
{
    protected $table = "what_learn_instructor_course";
    protected $primaryKey = "learn_id";
    protected $fillable = ["learn_id, learn, course_id"];
}