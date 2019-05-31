<?php

namespace App\Http\Controllers;

use App\Common\Auth\JwtAuth;
use App\Http\Response\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class UserBaseController extends BaseController
{
    use ResponseJson;
    public $uid;
    public function __construct()
    {
        $this->uid = JwtAuth::getInstance()->getUid();
    }
}
