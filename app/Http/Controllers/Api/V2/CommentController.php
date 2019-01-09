<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Comment\CommentAdd;
use App\Http\Requests\Comment\CommentId;
use App\Http\Requests\Comment\PostId;
use App\Services\Comment\CommentService;

class CommentController extends BaseController
{
    function commentAdd(CommentAdd $request, CommentService $service)
    {
        $params = $request->all();

        $custom = $request->user();
        $params['created_from'] = 0;
        $params['created_by'] = $custom['id'];
        $params['status'] = 1;

        $res = $service->commentAdd($params);
        return $this->formatReturn($res);
    }

    function commentList(PostId $request, CommentService $service)
    {
        $params = $request->all();
        $res = $service->commentList($params, 1);
        return $this->formatReturn($res);
    }

    function commentDetail(CommentId $request, CommentService $service)
    {
        $params = $request->all();
        $res = $service->commentDetail($params);
        return $this->formatReturn($res);
    }

    function commentUpdate(CommentId $request, CommentService $service)
    {
        $params = $request->all();
        $res = $service->commentUpdate($params);
        return $this->formatReturn($res);
    }
}