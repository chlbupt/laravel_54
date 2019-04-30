<?php

// 后台管理
Route::group(['prefix' => 'admin'], function(){
    // 登录展示界面
    Route::get('/login', 'Admin\LoginController@index');
    // 登录行为
    Route::post('/login', 'Admin\LoginController@login');
    // 登出行为
    Route::get('/logout', 'Admin\LoginController@logout');
    // 首页
    Route::group(['middleware' => 'auth:admin'], function(){
        Route::get('/home', 'Admin\HomeController@index');
        Route::get('/users', 'Admin\UserController@index');
        Route::get('/users/create', 'Admin\UserController@create');
        Route::post('/users/store', 'Admin\UserController@store');
        // 审核模块
        Route::get('/posts', 'Admin\PostController@index');
        Route::Post('/posts/{post}/status', 'Admin\PostController@status');
    });
});