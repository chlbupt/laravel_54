<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    function index()
    {
        $user = \Auth::user();
        $notices = $user->notices;
        return view('notice.index', compact('notices'));
    }
}
