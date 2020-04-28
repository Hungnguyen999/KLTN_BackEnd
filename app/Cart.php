<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "cart";
    protected $primaryKey = "user_id";
    public $incrementing = false;


}