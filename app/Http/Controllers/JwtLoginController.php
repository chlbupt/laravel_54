<?php

namespace App\Http\Controllers;

use App\Common\Auth\JwtAuth;
use App\Common\Err\ApiErrDesc;
use App\Exceptions\ApiException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Response\ResponseJson;

class JwtLoginController extends UserBaseController
{
    use ResponseJson;

    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $res = User::where('email', $email)->first();
        if(!$res){
            throw new ApiException(ApiErrDesc::ERR_USER_NOT_EXIST);
        }
        if(!password_verify($password, $res['password'])){
            throw new ApiException(ApiErrDesc::ERR_PASSWORD);
        }
        $jwtAuth = JwtAuth::getInstance();
        $token = $jwtAuth->setUid($res['id'])->encode()->getToken();
        return $this->jsonSuccessData([
            'token' => $token
        ]);
    }
}
