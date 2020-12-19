<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemilihan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'mahasiswa_id','paslons_id','foto','status'
    ];
    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class,'mahasiswa_id');
    }
    public function paslon(){
        return $this->belongsTo(Paslon::class,'paslons_id');
    }
    public function getFotoAttribute($value){
        if($value!=null){
            $value =  url('assets/images/id_card/'.$value);
        }
        return $value;
    }
}
