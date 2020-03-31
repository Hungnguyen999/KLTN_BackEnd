<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = "usercard";
    protected $primaryKey = "user_id";
    public $incrementing = false;
}