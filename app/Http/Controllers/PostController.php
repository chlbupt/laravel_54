<?php

namespace App\Http\Controllers;

use \App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 文章列表
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
//        $faker_data = factory(Post::class, 1)->create();
        return view('post.index', compact('posts'));
    }
    // 文章详情
    public function show(Post $post)
    {
        return view('post.show', compact('post'));
    }
    // 创建文章
    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);
        Post::create(request(['title', 'content']));
        return redirect('/posts');
    }
    // 编辑文章
    public function edit(Post $post)
    {
        return view('post/edit', compact('post'));
    }
    public function update(Post $post)
    {
        // 验证
        $this->validate(request(),[
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);
        // 逻辑
        $post->title = request('title');
        $post->content = request('content');
        $post->save();
        //渲染
        return redirect("/posts/{$post->id}");
    }
    // 删除文章
    public function delete(Post $post)
    {
        // TODO: 用户权限验证

        $post->delete();
        return redirect('/posts');
    }
}
