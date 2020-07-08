<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class PriceTier extends Model
{
    protected $table = "pricetier";
    protected $primaryKey = "priceTier_id";
    public $timestamps = false;
}