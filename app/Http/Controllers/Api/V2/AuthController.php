<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use App\Http\Requests\Custom\CustomLogin;

class AuthController extends BaseController
{
    protected $guard;

    public function __construct()
    {
        $this->guard = Auth::guard('api');
    }

    public function login(CustomLogin $request)
    {
        $params = $request->all();
        $token = $this->guard->attempt([
            'email' => $params['email'],
            'password' => $params['password']
        ]);

        if ($token) {
            $data = [
                'token_type' => 'bearer',
                'token' => $token,
                'ttl' => config('jwt.ttl')*60,
                'refresh_ttl' => config('jwt.refresh_ttl')*60
            ];
            return $this->formatReturn(['state' => true, 'data' => $data]);
        } else {
            return $this->formatReturn(['state' => false, 'error' => '登录失败']);
        }
    }

    public function user()
    {
        $member = $this->guard->user();
        return $this->formatReturn(['state' => true, 'data' => $member]);
    }

    public function logout()
    {
        $this->guard->logout();
        return $this->formatReturn(['state' => true]);
    }

    public function refresh()
    {
        try {
            $ret = [
                'token' => $this->guard->refresh()
            ];
        }catch (\Exception $e) {
            throw new TokenBlacklistedException();
        }
        return $this->formatReturn(['state' => true, 'data' => $ret]);
    }
}