<?php

namespace App\Http\Controllers\Admin\Post;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Post\PostAdd;
use App\Http\Requests\Post\PostId;
use App\Http\Requests\Post\TopicId;
use Illuminate\Http\Request;
use App\Services\Post\PostService;

class PostController extends BaseController
{
    function postAdd(PostAdd $request, PostService $service)
    {
        $params = $request->all();

        $custom = $request->user();
        $params['created_from'] = 1;
        $params['created_by'] = $custom['id'];

        $res = $service->postAdd($params);
        return $this->formatReturn($res);
    }

    function postList(TopicId $request, PostService $service)
    {
        $params = $request->all();
        $res = $service->postList($params);
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
}