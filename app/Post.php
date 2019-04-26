<?php

namespace App;

use App\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
    public function zan($user_id)
    {
        return $this->hasMany('App\zan')->where('user_id', $user_id);
    }
    public function zans()
    {
        return $this->hasMany(\App\Zan::class);
    }
}
