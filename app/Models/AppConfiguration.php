<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    use HasFactory;
    public function getDetailAttribute($value){
        return json_decode($value,true);
    }
}
