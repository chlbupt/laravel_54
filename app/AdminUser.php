<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
//use App\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $rememberTokenName = '';
    protected $guarded = [];
    // 当前用户的所有角色
    function roles()
    {
        return $this->belongsToMany(\App\AdminRole::class, 'admin_role_user', 'user_id', 'role_id')->withPivot(['role_id', 'user_id']);
    }
    // 判断用户是否有某些角色
    function isInRoles($roles)
    {
        return !! $roles->intersect($this->roles)->count();
    }
    // 分配角色
    function assignRole($role)
    {
        return $this->roles()->save($role);
    }
    // 取消用户角色
    function deleteRole($role)
    {
        return $this->roles()->detach($role);
    }
    // 用户是否有权限
    function hasPermission($permission)
    {
        return $this->isInRoles($permission->roles);
    }
}
