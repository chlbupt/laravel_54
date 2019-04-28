<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use App\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password',
    ];

    // 用戶的文章列表
    public function posts()
    {
        return $this->hasMany(\App\Post::class, 'user_id');
    }

    // 關注我的粉絲模型
    public function fans()
    {
        return $this->hasMany(\App\Fan::class, 'star_id');
    }
    // 我關注的fan模型
    public function stars()
    {
        return $this->hasMany(\App\Fan::class, 'fan_id');
    }
    // 是否被關注
    public function hasFan($uid){
        return $this->fans()->where('fan_id', $uid)->count();
    }
    // 是否關注某人
    public function hasStar($uid)
    {
        return $this->stars()->where('star_id', $uid)->count();
    }
}
