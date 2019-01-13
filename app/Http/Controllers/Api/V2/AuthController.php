<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use App\Http\Requests\Custom\CustomLogin;
use App\Http\Requests\Custom\CustomRegister;
use App\Services\User\UserService;
use Illuminate\Http\Request;

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
        $time = time();
        $token = $this->guard->attempt([
            'email' => $params['email'],
            'password' => $params['password']
        ]);

        if ($token) {
            // 激活判定
            $member = $this->guard->user();
            if (!$member->active) {
                $this->guard->logout();
                return $this->formatReturn(['state' => false, 'error' => '登录失败,对应账号未激活']);
            }
            $data = [
                'token_type' => 'bearer',
                'token' => $token,
                'ttl' => $time + config('jwt.ttl')*60,
                'refresh_ttl' => $time + config('jwt.refresh_ttl')*60
            ];
            return $this->formatReturn(['state' => true, 'data' => $data]);
        } else {
            return $this->formatReturn(['state' => false, 'error' => '登录失败']);
        }
    }

    public function user(Request $request, UserService $service)
    {
        $params = $request->all();
        if (isset($params['user_id'])) {
            $member = $service->getMember($params['user_id']);
            if (!$member) {
                return $this->formatReturn(['state' => false, 'error' => '未查到对应用户']);
            }
        } else {
            $check = $this->guard->check();
            if (!$check) {
                return $this->formatReturn(['state' => false, 'error' => '用户认证失败', 'message' => 'login']);
            }
            $member = $this->guard->user();
        }

        if (!$member->avatar) {
            $member->avatar = config('app.url').'/image/avatar/avatar.png';
        }
        if (!$member->avatar_big) {
            $member->avatar_big = config('app.url').'/image/avatar/avatar_big.png';
        }
        if (!$member->sign) {
            $member->sign = '个性签名';
        }
        return $this->formatReturn(['state' => true, 'data' => $member]);
    }

    public function userEdit(Request $request, UserService $service)
    {
        $params = $request->all();
        $user = $this->guard->user();
        $params['user_id'] = $user['id'];
        $res = $service->userEdit($params);
        return $this->formatReturn($res);
    }

    public function register(CustomRegister $request, UserService $service)
    {
        $params = $request->all();
        $res = $service->register($params);
        return $this->formatReturn($res);
    }

    public function logout()
    {
        $this->guard->logout();
        return $this->formatReturn(['state' => true]);
    }

    public function refresh()
    {
        try {
            $time = time();
            $data = [
                'token_type' => 'bearer',
                'token' => $this->guard->refresh(),
                'ttl' => $time + config('jwt.ttl')*60
            ];
        }catch (\Exception $e) {
            throw new TokenBlacklistedException();
        }
        return $this->formatReturn(['state' => true, 'data' => $data]);
    }
}