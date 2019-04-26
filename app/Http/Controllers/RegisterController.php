<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class RegisterController extends Controller
{
    function index()
    {
        return view('register.index');
    }
    function register()
    {
        // 验证逻辑
        $this->validate(request(), [
            'name' => 'required|min:3|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:10|confirmed',
        ]);
        // 业务逻辑
        $name = request('name');
        $email = request('email');
        $password = bcrypt(request('password'));
        $user = User::create(compact('name', 'email', 'password'));
        // 渲染模板
        return redirect('/login');
    }
}
