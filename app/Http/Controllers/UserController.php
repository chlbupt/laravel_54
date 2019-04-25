<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    function setting()
    {
        return view('user.setting');
    }
    function settingStore()
    {

    }
}
