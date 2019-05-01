<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use App\Model;

class AdminRole extends Model
{
    protected $table = 'admin_roles';
    // 当前用户的所有权限
    function permissions()
    {
        return $this->belongsToMany(\App\AdminPermission::class, 'admin_permission_role', 'role_id', 'permission_id')->withPivot('role_id', 'permission_id');
    }
    // 授予权限
    function grantPermission($permission)
    {
        return $this->permissions()->save($permission);
    }
    // 取消权限
    function deletePermission($permission)
    {
        return $this->permissions()->detach($permission);
    }
    // 角色是否有权
    function hasPermission($permission)
    {
        return $this->permissions->contains($permission);
    }
}
