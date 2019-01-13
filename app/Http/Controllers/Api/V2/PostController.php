<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\PostAdd;
use App\Http\Requests\Post\PostId;
use App\Http\Requests\Post\TopicId;
use App\Services\Post\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    protected $guard;

    public function __construct()
    {
        $this->guard = Auth::guard('api');
    }

    function postAdd(PostAdd $request, PostService $service)
    {
        $params = $request->all();

        $custom = $request->user();
        $params['created_from'] = 0;
        $params['created_by'] = $custom['id'];
        $params['status'] = 1;
        $params['comment_audit'] = 1;

        $res = $service->postAdd($params);
        return $this->formatReturn($res);
    }

    function postList(Request $request, PostService $service)
    {
        $params = $request->all();
        $res = $service->postList($params, 1);
        return $this->formatReturn($res);
    }

    function postDetail(PostId $request, PostService $service)
    {
        $params = $request->all();
        $check = $this->guard->check();
        if ($check) {
            $user = $this->guard->user();
            $params['user_id'] = $user['id'];
        } else {
            $params['user_id'] = 0;
        }
        $res = $service->postDetail($params);
        return $this->formatReturn($res);
    }

    function postUpdate(PostId $request, PostService $service)
    {
        $params = $request->all();
        $res = $service->postUpdate($params);
        return $this->formatReturn($res);
    }

    function postInteract(PostId $request, PostService $service)
    {
        $params = $request->all();

        $custom = $request->user();
        $params['user_id'] = $custom['id'];

        $res = $service->postInteract($params);
        return $this->formatReturn($res);
    }
}