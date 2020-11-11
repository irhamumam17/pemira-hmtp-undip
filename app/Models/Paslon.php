<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paslon extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function ketua(){
        return $this->belongsTo(Mahasiswa::class,'ketua_mahasiswa_id');
    }
    public function wakil(){
        return $this->belongsTo(Mahasiswa::class,'wakil_mahasiswa_id');
    }
    public function getFotoAttribute($value){
        return url('assets/pemira/images/calon/'.$value);
    }
    // public function getMisiAttribute($value){
    //     return html_entity_decode($value);
    // }
}
