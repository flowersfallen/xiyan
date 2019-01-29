<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseController;

class WeChatController extends BaseController
{

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');

        return $app->server->serve();
    }
}