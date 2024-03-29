<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = "topic";
    protected $primaryKey = "topic_id";
    protected $fillable = ['topic_id','topic_name','name', 'category_id', 'icon_class','disable'];

    public function courseEnable() {
        return $this
            ->belongsToMany(InstructorCourse::class,'topic_course','topic_id','course_id')
            ->where('disable', false);
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id','category_id');
    }
}