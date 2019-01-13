<?php

namespace App\Services\Notice;

use App\Services\BaseService;
use App\Models\Notice\Notice;

class NoticeService extends BaseService
{
    public function noticeList($params)
    {
        $pagesize = isset($params['pagesize']) ? $params['pagesize'] : 15;

        $rows = Notice::query()->select('notices.id', 'notices.from', 'notices.post_id', 'notices.title', 'notices.message', 'notices.created_at', 'users.name', 'users.avatar')
            ->leftJoin('users', 'notices.from', '=', 'users.id')
            ->where('to', $params['user_id'])
            ->orderBy('id', 'desc')
            ->paginate($pagesize);

        $rows->each(function ($item) {
            if (!$item->avatar) {
                $item->avatar = config('app.url').'/image/avatar/avatar.png';
            }
        });

        $send = [
            'state' => true,
            'data' => [
                'list' => $rows->items(),
                'total' => $rows->total()
            ]
        ];

        return $send;
    }
}