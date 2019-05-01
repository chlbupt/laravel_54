<?php

namespace App\Http\Controllers\Admin;

use App\AdminRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AdminUser;

class RoleController extends Controller
{
    // 展示界面
    function index()
    {
        $roles = \App\AdminRole::paginate(10);
//        dd($roles);
        return view('admin.role.index', compact('roles'));
    }
    function create()
    {
        return view('admin.role.create');
    }

    function store()
    {
        // 验证
        $this->validate(request(), [
            'name' => 'required|min:3',
            'description' => 'required',
        ]);
        \App\AdminRole::create(request(['name', 'description']));
        return redirect('/admin/roles');
    }
    function permission(AdminRole $role){
        // 获取所有的权限
        $permissions = \App\AdminPermission::all();
        // 获取当前用户的权限
        $myPermissions = $role->permissions;
        return view('admin.role.permission', compact('permissions', 'myPermissions', 'role'));
    }
    function storePermission(AdminRole $role)
    {
        // 验证
        $this->validate(request(), [
            'permissions' => 'required|array',
        ]);
        // 获取所有的权限
        $permissions = \App\AdminPermission::findMany(request('permissions'));
        // 获取当前用户的权限
        $myPermissions = $role->permissions;
        // 增加的权限
        $addPermissions = $permissions->diff($myPermissions);
        foreach($addPermissions as $permission)
        {
            $role->grantPermission($permission);
        }
        // 删除的权限
        $deletePermissions = $myPermissions->diff($permissions);
        foreach($deletePermissions as $permission)
        {
            $role->deletePermission($permission);
        }
        return back();
    }
}
