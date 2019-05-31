<?php

namespace App\Common\Err;

class ApiErrDesc
{
    const SUCCESS = [0, 'Success'];
    const ERR_EXPIRE = [1, '登录过期'];
    const ERR_PARAMS = [2, '参数错误'];
    const ERR_UNKNOWN = [100, '未知错误'];
    const ERR_USER_NOT_EXIST = [1000, '用户不存在'];
    const ERR_PASSWORD = [1001, '密码错误'];
}