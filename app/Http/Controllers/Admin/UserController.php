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
    // 用户角色管理
    function role(AdminUser $user)
    {
        $roles = \App\AdminRole::all();
        $myRoles = $user->roles;
        return view('admin.user.role', compact('user', 'roles', 'myRoles'));
    }
    function roleStore(AdminUser $user){
        $this->validate(request(), [
            'roles' => 'required|array',
        ]);
        $roles = \App\AdminRole::findMany(request('roles'));
        $myRoles = $user->roles;
        // 要增加的
        $addRoles = $roles->diff($myRoles);
        foreach($addRoles as $role){
            $user->assignRole($role);
        }
        //  要删除的
        $deleteRoles = $myRoles->diff($roles);
        foreach($deleteRoles as $role){
            $user->deleteRole($role);
        }
        return back();
    }
}
