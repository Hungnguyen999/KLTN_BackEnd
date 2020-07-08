<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class MoneyType extends Model
{
    protected $table = "moneyType";
    protected $primaryKey = "moneyType_id";
    public $timestamps = false;
}