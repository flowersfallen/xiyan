<?php

namespace App\Http\Controllers\Admin\Custom;

use App\Http\Requests\Custom\CustomLogin;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Common\FileRequest;

class CustomController extends BaseController
{
    protected $guard;

    public function __construct()
    {
        $this->guard = Auth::guard('admin');
    }

    public function login(CustomLogin $request)
    {
        $params = $request->all();
        $attempt = $this->guard->attempt([
            'email' => $params['email'],
            'password' => $params['password']
        ]);

        if ($attempt) {
            return $this->formatReturn(['state' => true]);
        } else {
            return $this->formatReturn(['state' => false, 'error' => '登录失败']);
        }
    }

    public function custom()
    {
        $custom = $this->guard->user();
        return $this->formatReturn(['state' => true, 'data' => $custom]);
    }

    public function logout()
    {
        $this->guard->logout();
        return $this->formatReturn(['state' => true]);
    }

    public function file(FileRequest $request)
    {
        $path = $request->file('file')->store('public/'.date('Ymd'));

        return $this->formatReturn([
            'state' => true,
            'data' => [
                'path' => $path,
                'url' =>  path_url($path)
            ]
        ]);
    }
}
