<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

class NoticeController extends Controller
{
    function index()
    {
        $notices = \App\Notice::all();
        return view('admin.notice.index', compact('notices'));
    }

    function create()
    {
        return view('admin.notice.create');
    }

    function store()
    {
        // 验证
        $this->validate(request(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        $notice = \App\Notice::create(request(['title', 'content']));
        dispatch(new \App\Jobs\SendMessage($notice));
        return redirect('/admin/notices');
    }

}
