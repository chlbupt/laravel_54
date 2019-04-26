<?php

namespace App;

use App\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
