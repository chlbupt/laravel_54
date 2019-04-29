<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Topic;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    function show(Topic $topic)
    {
        // 带文章数的专题
        $topic = Topic::withCount(['postTopics'])->find($topic->id);
        // 专题下的文章列表
        $posts = $topic->posts()->orderBy('created_at', 'desc')->take(10)->get();
        // 不属于专题的文章
//        DB::connection()->enableQueryLog();
        $myposts = \App\Post::authorBy(\Auth::id())->topicNotBy($topic->id)->get();
//        $log = DB::getQueryLog();
//        dd($log);
        return view('topic.show', compact('topic', 'posts', 'myposts'));
    }
    function submit(Topic $topic)
    {
        // 验证
        $this->validate(request(), [
            'post_ids' => 'required|array',
        ]);
        // 逻辑
        $topic_id = $topic->id;
        $post_ids = request('post_ids');
        foreach($post_ids as $post_id)
        {
            \App\PostTopic::firstOrCreate(compact('topic_id', 'post_id'));
        }
        // 渲染
        return back();
    }
}
