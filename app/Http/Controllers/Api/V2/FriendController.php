<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\User\UserService;
use App\Http\Requests\User\FollowId;

class FriendController extends BaseController
{
    public function UserFollow(FollowId $request, UserService $service)
    {
        $params = $request->all();

        $user = $request->user();
        $params['user_id'] = $user['id'];

        $data = $service->userFollow($params);
        return $this->formatReturn($data);
    }

    public function userUnfollow(FollowId $request, UserService $service)
    {
        $params = $request->all();

        $user = $request->user();
        $params['user_id'] = $user['id'];

        $data = $service->userUnfollow($params);
        return $this->formatReturn($data);
    }
}