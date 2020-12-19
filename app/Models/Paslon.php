<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paslon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'nomor_urut','ketua_mahasiswa_id','wakil_mahasiswa_id','foto','visi','misi'
    ];
    
    public function ketua(){
        return $this->belongsTo(Mahasiswa::class,'ketua_mahasiswa_id');
    }
    public function wakil(){
        return $this->belongsTo(Mahasiswa::class,'wakil_mahasiswa_id');
    }
    public function pemilihan(){
        return $this->hasMany(Pemilihan::class,'paslons_id');
    }
    public function pemilihanTemp(){
        return $this->hasMany(PemilihanTemp::class,'paslon_id');
    }
    public function getFotoAttribute($value){
        return url('assets/pemira/images/calon/'.$value);
    }
}
