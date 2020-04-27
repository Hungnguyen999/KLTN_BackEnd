<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "message";
    protected $primaryKey = "message_id";
    protected $fillable = ['message', 'fromUser', 'toUser', 'mute'];

    public function from() {
        return $this->belongsTo(User::class,'fromUser','user_id');
    }

    public function to() {
        return $this->belongsTo(User::class,'toUser','user_id');
    }

}