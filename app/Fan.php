<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use App\Model;

class Fan extends Model
{
    function fuser()
    {
        return $this->hasone(\App\User::class, 'id', 'fan_id');
    }

    function suser(){
        return $this->hasone(\App\User::class, 'id', 'star_id');
    }
}
