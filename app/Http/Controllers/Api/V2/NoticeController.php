<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\Notice\NoticeService;

class NoticeController extends BaseController
{
    function noticeList(Request $request, NoticeService $service)
    {
        $params = $request->all();
        $custom = $request->user();
        $params['user_id'] = $custom['id'];
        $res = $service->noticeList($params);
        return $this->formatReturn($res);
    }
}