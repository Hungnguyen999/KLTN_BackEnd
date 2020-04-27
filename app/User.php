<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $table = "user";
    public $incrementing = false;
    public $timestamps = true;
    protected $primaryKey = "user_id";
    protected $fillable = [
        'user_id', 'password', 'phone', 'name', 'cardNumber','description'
    ];

    protected $hidden = ['password'];

    public function card() {
        return $this->hasOne(Card::class,'user_id','user_id');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    /**
     * @inheritDoc
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    public function getJWTCustomClaims() {
        return [];
    }
}
