<?php

namespace App\Http\Middleware;

use App\Common\Err\ApiErrDesc;
use App\Exceptions\ApiException;
use App\Http\Response\ResponseJson;
use Closure;
use App\Common\Auth\JwtAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    use ResponseJson;
    public function handle($request, Closure $next)
    {
        $token = $request->input('token');
//        dd($token);
        if($token){
            $jwtAuth = JwtAuth::getInstance();
            $jwtAuth->setToken($token);
            if($jwtAuth->validate() && $jwtAuth->verify()){
                return $next($request);
            }else{
                throw new ApiException(ApiErrDesc::ERR_EXPIRE);
            }
        }else{
            throw new ApiException(ApiErrDesc::ERR_PARAMS);
        }
    }
}
