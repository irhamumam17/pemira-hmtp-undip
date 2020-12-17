<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'username','password','status','last_login'
    ];

    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'status' => 0,
        'last_login' => null,
    ];

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }
}
