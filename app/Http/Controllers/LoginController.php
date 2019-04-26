<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    function index()
    {
        return view('login.index');
    }
    function login()
    {
        // 验证
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:10',
            'is_remember' => 'integer',
        ]);
        // 业务
        $user = request(['email', 'password']);
        $is_remember = request('is_remember');
        if(Auth::attempt($user, $is_remember)){
            return redirect('/posts');
        }
        // 渲染
        return back()->withErrors('邮箱密码不匹配');
    }
    function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
