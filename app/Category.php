<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "category";
    protected $primaryKey = "category_id";

    protected $fillable = [
        'category_id',
        'name',
        'icon_class'
    ];
    public function topics() {
        return $this->hasMany(Topic::class,'category_id','category_id');
    }

    public function topicsEnable() {
        return $this->hasMany(Topic::class,'category_id','category_id')->where('disable', false);
    }
}