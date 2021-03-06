<?php

namespace App;

use App\Model;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\searchable;

class Post extends Model
{
//    use searchable;
    public function searchableAs()
    {
        return 'post';
    }
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
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
    // 属于某个作者的文章
    public function scopeAuthorBy(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
    // 专题文章关联模型
    public function postTopics()
    {
        return $this->hasMany(\App\PostTopic::class, 'post_id');
    }
    // 不属于某个专题的文章
    public function scopeTopicNotBy(Builder $query, $topic_id)
    {
        return $query->doesntHave('postTopics', 'and', function($q) use($topic_id) {
            $q->where('topic_id', $topic_id);
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('available', function(Builder $builder){
            $builder->whereIn('status', [0, 1 ]);
        });
    }
}
