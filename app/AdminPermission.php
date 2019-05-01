<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use App\Model;

class AdminPermission extends Model
{
    protected $table = 'admin_permissions';
    // 权限对应的角色有哪些
    function roles()
    {
        return $this->belongsToMany(\App\AdminRole::class, 'admin_permission_role', 'permission_id', 'role_id');
    }
}
