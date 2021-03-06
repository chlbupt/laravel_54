<?php

namespace App\Http\Controllers;

use App\Common\Auth\JwtAuth;
use App\Common\Err\ApiErrDesc;
use App\Exceptions\ApiException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Response\ResponseJson;
use Illuminate\Support\Facades\Redis;

class UserController extends UserBaseController
{
    use ResponseJson;
    public function __construct()
    {
        parent::__construct();
    }

    function setting()
    {
        $user = \Auth::user();
        return view('user.setting', compact('user'));
    }
    function settingStore(Request $request)
    {
        $user = User::find(\Auth::id());
        // 驗證
        $this->validate($request, [
            'name' => 'required|min:3',
        ]);
        // 邏輯
        $name = request('name');
        if($name != \Auth::user()->name)
        {
            if(User::where('name', $name)->count() > 0){
                return back()->withErrors(['message' => '用户名称已经被注册']);
            }
        }
        $user->name = $name;
        if($request->file('avatar') ){
            $avatar_path = env('AVATAR_PATH', '/uploads/avatar');
            $path = $request->file('avatar')->store($avatar_path);
//            dd($path);
            $user->avatar = '/storage/'. $path;
        }
        // 渲染
        $user->save();
        return back();
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
//        dd($fusers);
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
    function info(){
        $jwtAuth = JwtAuth::getInstance();
        $uid = $jwtAuth->getUid();
        $user = User::where('id', $uid)->first();
        if(!$user){
            throw new ApiException(ApiErrDesc::ERR_USER_NOT_EXIST);
        }
        return $this->jsonSuccessData([
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => $user['avatar']
        ]);
    }
    function infoCache()
    {
//        dd($this->uid);
        $jwtAuth = JwtAuth::getInstance();
        $this->uid = $jwtAuth->getUid();
        $cacheUserInfo = Redis::get('uid:' . $this->uid);
        if(!$cacheUserInfo){
            $user = User::where('id', $this->uid)->first();
            if(!$user){
                throw new ApiException(ApiErrDesc::ERR_USER_NOT_EXIST);
            }
            Redis::setex('uid:' . $this->uid, 3600, json_encode($user->toArray()));
        }else{
            $user = json_decode($cacheUserInfo);
        }
        return $this->jsonSuccessData([
            'name' => $user->name,
            'email' => $user['email'],
            'avatar' => $user['avatar']
        ]);
    }
}
