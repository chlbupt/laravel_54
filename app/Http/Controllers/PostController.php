<?php

namespace App\Http\Controllers;

use App\Post;
use App\Zan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // 文章列表
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->withCount(['comments', 'zans'])->paginate(5);
//        $faker_data = factory(Post::class, 1)->create();
        return view('post.index', compact('posts'));
    }
    // 文章详情
    public function show(Post $post)
    {
        $post->load('comments');
        return view('post.show', compact('post'));
    }
    // 创建文章
    public function create()
    {
        return view('post.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);
        $user_id = Auth::id();
        $params = array_merge(request(['title', 'content']), compact('user_id'));
        Post::create($params);
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
        $this->authorize('update', $post);
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
        $this->authorize('delete', $post);
        $post->delete();
        return redirect('/posts');
    }
    // 提交评论
    public function comment(Post $post)
    {
        // 验证
        $this->validate(request(), [
            'content' => 'required|min:3',
        ]);
        // 业务
        $comment = new \App\Comment();
        $comment->user_id = \Auth::id();
        $comment->content = request('content');
//        dd($post->comments);
        $post->comments()->save($comment);
        // 渲染
        return back();
    }
    // 赞
    function zan(Post $post)
    {
        $param = [
            'user_id' => \Auth::id(),
            'post_id' => $post->id,
        ];
        Zan::firstOrCreate($param);
        return back();
    }
    // 取消赞
    function unzan(Post $post)
    {
        $post->zan(\Auth::id())->delete();
        return back();
    }
    // 搜索
    public function search()
    {
        // 驗證
        $this->validate(request(), [
            'query' => 'required',
        ]);
        // 邏輯
        $query = request('query');
        $posts = \App\Post::search($query)->paginate(2);
        // 渲染
        return view('post.search', compact('posts', 'query'));
    }
}
