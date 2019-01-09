<?php

namespace App\Http\Controllers\Admin\Topic;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Topic\TopicAdd;
use App\Http\Requests\Topic\TopicId;
use Illuminate\Http\Request;
use App\Services\Topic\TopicService;

class TopicController extends BaseController
{
    function topicAdd(TopicAdd $request, TopicService $service)
    {
        $params = $request->all();

        $custom = $request->user();
        $params['created_from'] = 1;
        $params['created_by'] = $custom['id'];
        $params['status'] = 1;
        $params['post_audit'] = 1;

        $res = $service->topicAdd($params);
        return $this->formatReturn($res);
    }

    function topicList(Request $request, TopicService $service)
    {
        $params = $request->all();
        $res = $service->topicList($params);
        return $this->formatReturn($res);
    }

    function topicDetail(TopicId $request, TopicService $service)
    {
        $params = $request->all();
        $res = $service->topicDetail($params);
        return $this->formatReturn($res);
    }

    function topicUpdate(TopicId $request, TopicService $service)
    {
        $params = $request->all();
        $res = $service->topicUpdate($params);
        return $this->formatReturn($res);
    }
}