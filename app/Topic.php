<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use \App\Model;

class Topic extends Model
{
    function posts(){
        return $this->belongsToMany(\App\Post::class, 'post_topics', 'topic_id', 'post_id');
    }
    // 专题的文章数
    function postTopics()
    {
        return $this->hasMany(\App\PostTopic::class, 'topic_id');
    }
}
