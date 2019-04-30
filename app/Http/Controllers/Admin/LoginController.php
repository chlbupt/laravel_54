<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/30
 * Time: 12:25
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\Controller;


class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login.index');
    }

    public function login()
    {
        //  验证
        $this->validate(request(), [
            'name' => 'required|string|min:3',
            'password' => 'required|min:5|max:10',
        ]);
        //  逻辑
        $user = request(['name', 'password']);
        if(\Auth::guard('admin')->attempt($user)){
            return redirect('/admin/home');
        }
        // 渲染
        return back()->withInput()->withErrors('用户名密码不匹配');
    }
    public function logout()
    {
        \Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}