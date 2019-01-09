<?php

namespace App\Http\Controllers;

use App\Mail\Regist;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function mail()
    {
        $to = '1051268138@qq.com';
        $activeUrl = 'http://www.baidu.com';
        Mail::to($to)->send(new Regist($activeUrl));
    }
}
