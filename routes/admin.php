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
        // 首页
        Route::get('/home', 'Admin\HomeController@index');
        Route::group(['middleware' => 'can:system'], function (){
            // 管理员模块
            Route::get('/users', 'Admin\UserController@index');
            Route::get('/users/create', 'Admin\UserController@create');
            Route::post('/users/store', 'Admin\UserController@store');
            Route::get('/users/{user}/role', 'Admin\UserController@role');
            Route::post('/users/{user}/role', 'Admin\UserController@roleStore');
            // 角色模块
            Route::get('/roles', 'Admin\RoleController@index');
            Route::get('/roles/create', 'Admin\RoleController@create');
            Route::post('/roles/store', 'Admin\RoleController@store');
            Route::get('/roles/{role}/permission', 'Admin\RoleController@permission');
            Route::post('/roles/{role}/permission', 'Admin\RoleController@storePermission');
            // 权限模块
            Route::get('/permissions', 'Admin\PermissionController@index');
            Route::get('/permissions/create', 'Admin\PermissionController@create');
            Route::post('/permissions/store', 'Admin\PermissionController@store');
        });
        Route::group(['middleware' => 'can:post'], function(){
            // 审核模块
            Route::get('/posts', 'Admin\PostController@index');
            Route::Post('/posts/{post}/status', 'Admin\PostController@status');
        });

    });
});