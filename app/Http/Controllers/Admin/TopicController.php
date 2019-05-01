<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

class TopicController extends Controller
{
    function index()
    {
        $topics = \App\Topic::all();
        return view('admin.topic.index', compact('topics'));
    }

    function create()
    {
        return view('admin.topic.create');
    }

    function store()
    {
        // 验证
        $this->validate(request(), [
            'name' => 'required|string|min:3',
        ]);
        \App\Topic::create(['name' => request('name')]);
        return redirect('/admin/topics');
    }

    function destroy(\App\Topic $topic)
    {
        $topic->delete();
        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}
