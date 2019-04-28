<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    function setting()
    {
        $user = \Auth::user();
        return view('user.setting', compact('user'));
    }
    function settingStore(Request $request)
    {
        // 驗證
        $this->validate($request, [
            'name' => 'required|min:3',
        ]);
        // 邏輯

        // 渲染
        return redirect('/user/me/setting');
    }
    // 個人頁面
    function show(User $user)
    {
        // 個人信息，關注/粉絲/文章數
        $user = User::withCount(['fans', 'stars', 'posts'])->find($user->id);
        // 文章列表，10條
        $posts = $user->posts()->orderBy('created_at', 'desc')->take(10)->get();
        // 關注列表，包含關注/粉絲/文章數
        $stars = $user->stars;
        $susers = User::whereIn('id', $stars->pluck('star_id'))->withCount(['fans', 'stars', 'posts'])->get();
        // 粉絲列表，包含關注/粉絲/文章數
        $fans = $user->fans;
        $fusers =  User::whereIn('id', $fans->pluck('fan_id'))->withCount(['fans', 'stars', 'posts'])->get();
        return view('user.show', compact('user', 'posts', 'susers', 'fusers'));
    }

    // 關注用戶
    function fan(User $user)
    {
        $me = \Auth::user();
        \App\Fan::firstOrCreate(['fan_id' => $me->id, 'star_id' => $user->id]);
        return [
            'error' => 0,
            'msg' => ''
        ];
    }

    // 取消關注
    function unfan(User $user)
    {
        $me = \Auth::user();
        \App\Fan::where('fan_id', $me->id)->where('star_id', $user->id)->delete();
        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}
