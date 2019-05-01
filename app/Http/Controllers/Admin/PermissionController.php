<?php

namespace App\Http\Controllers\Admin;

use App\AdminRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AdminUser;

class PermissionController extends Controller
{
    // 展示界面
    function index()
    {
        $permissions = \App\AdminPermission::paginate(10);
        return view('admin.permission.index', compact('permissions'));
    }
    function create()
    {
        return view('admin.permission.create');
    }

    function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3',
            'description' => 'required',
        ]);
//        DB::connection()->enableQueryLog();
        \App\AdminPermission::create(request(['name', 'description']));
//        dd(DB::getQueryLog());
        return redirect('/admin/permissions');
    }

}
