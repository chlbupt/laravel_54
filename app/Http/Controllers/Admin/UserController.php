<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AdminUser;

class UserController extends Controller
{
    // 展示界面
    function index()
    {
        $users = AdminUser::paginate(10);
        return view('admin.user.index', compact('users'));
    }
    function create()
    {
        return view('admin.user.create');
    }
    // 增加管理人员
    function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:3',
            'password' => 'required|min:3',
        ]);
        $name = request('name');
        $password = bcrypt(request('password'));
        AdminUser::create(compact('name', 'password'));
        return redirect('admin/users');
    }
}
