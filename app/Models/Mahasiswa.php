<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nim','name','angkatan','email','hint_password','password','start_session','end_session','status'
    ];
    protected $hidden = [
        'password','auth_session'
    ];

    protected $attributes = [
        'status' => 0,
        'start_session' => null,
        'end_session' => null,
    ];

    protected static function boot()
    {
        parent::boot();
        Mahasiswa::creating(function($model){
            $model->password = bcrypt($model->hint_password);
        });
    }

    public function calon_ketua(){
        return $this->hasOne(Paslon::class,'ketua_mahasiswa_id');
    }
    public function calon_wakil(){
        return $this->hasOne(Paslon::class,'wakil_mahasiswa_id');
    }
}
