<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\PostAdd;
use App\Http\Requests\Post\PostId;
use App\Http\Requests\Post\TopicId;
use App\Services\Post\PostService;
use Illuminate\Http\Request;

class PostController extends BaseController
{
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