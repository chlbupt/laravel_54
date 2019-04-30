<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

class PostController extends Controller
{
    function index()
    {
        $posts = Post::withoutGlobalScope('available')->where('status', 0)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.post.index', compact('posts'));
    }
    function status(Post $post)
    {
        $this->validate(request(), [
            'status' => 'required|in:1,-1',
        ]);
        $post->status = request('status');
        $post->save();
        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}
