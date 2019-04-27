<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
// 注册页面
Route::get('/register', 'RegisterController@index');
// 注册行为
Route::post('/register', 'RegisterController@register');
// 登录模块
Route::get('/login', 'LoginController@index');
// 登录行为
Route::post('/login', 'LoginController@login');
// 登出行为
Route::get('/logout', 'LoginController@logout');
// 个人设置
Route::get('/user/me/setting', 'UserController@setting');
// 个人设置行为
Route::post('/user/me/setting', 'UserController@settingStore');
// 文章列表
Route::get('/posts', 'PostController@index');
// 文章详情
Route::get('/posts/{post}', 'PostController@show')->where('post', '[0-9]+');
// 创建文章
Route::get('/posts/create', 'PostController@create');
Route::post('/posts', 'PostController@store');

// 编辑文章
Route::get('/posts/{post}/edit', 'PostController@edit');
Route::put('/posts/{post}', 'PostController@update');
// 删除文章
Route::get('/posts/{post}/delete', 'PostController@delete');
// 上傳圖片
//Route::post('/posts/image/upload', 'PostController@uploadImage');

// 提交评论
Route::post('/posts/{post}/comment', 'PostController@comment');
// 赞
Route::get('/posts/{post}/zan', 'PostController@zan');
// 取消赞
Route::get('/posts/{post}/unzan', 'PostController@unzan');
// 搜索
Route::get('/posts/search', 'PostController@search');