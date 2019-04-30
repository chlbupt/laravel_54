<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;

class HomeController extends Controller
{
    function index()
    {
        return view('admin.home.index');
    }
}
