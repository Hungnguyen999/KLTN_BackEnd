<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Hack extends Model
{
    protected $table = "hack";
    protected $primaryKey = "ID";
    protected $fillable = ['TEXT'];
    public $timestamps = false;
}