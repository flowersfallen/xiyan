<?php

namespace App\Services\Topic;

use App\Services\BaseService;
use App\Models\Topic\Topic;

class TopicService extends BaseService
{
    protected static $fields = [
        'title',
        'description',
        'thumb',
        'created_from',
        'created_by',
        'status',
    ];

    protected static $errors = [
        'save_error' => '保存失败',
        'row_not_exist' => '未查到对应记录',
        'no_privilege' => '没有权限',
    ];

    public function topicAdd($params)
    {
        $record = new Topic();
        $record->getConnection()->beginTransaction();
        try {
            foreach (self::$fields as $v) {
                if (isset($params[$v])) {
                    $record->$v = $params[$v];
                }
            }
            $record->status = 1;

            $insert = $record->save();
            if (!$insert) {
                throw new \Exception(self::$errors['save_error']);
            }

            $record->getConnection()->commit();
            $send = [
                'state' => true,
                'data' => $record
            ];
        } catch (\Exception $e) {
            $record->getConnection()->rollBack();
            $send = [
                'state' => false,
                'error' => $e->getMessage()
            ];
        }

        return $send;
    }

    public function topicList($params, $front = 0)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : false;
        $pagesize = isset($params['pagesize']) ? $params['pagesize'] : 15;
        $ids = false; // 后台非超管为圈主,客户端加入的圈子

        if (!$front) {
            $where = [
                ['status', '<>', 3]
            ];
        } else {
            $where = [
                ['status', '=', 1]
            ];
        }

        $rows = Topic::query()->where($where)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%');
            })->orderBy('id', 'desc')
            ->paginate($pagesize);

        if ($front) {
            $send = [
                'state' => true,
                'data' => [
                    'list' => $rows->items(),
                    'total' => $rows->total()
                ]
            ];
        } else {
            $send = [
                'state' => true,
                'data' => $rows
            ];
        }

        return $send;
    }

    public function topicDetail($params)
    {
        $row = Topic::query()->select('topics.*', 'users.name as creater_name')
            ->where([
                ['topics.status', '<>', 3],
                ['topics.id', '=', $params['id']],
            ])
            ->leftJoin('users', 'topics.created_by', '=', 'users.id')
            ->first();

        $send = [
            'state' => true,
            'data' => $row ? $row : []
        ];

        return $send;
    }

    public function groupUpdate($params)
    {
        $row = Group::query()->where([
            ['id', '=', $params['id']],
            ['status', '<>', 3]
        ])->first();
        if (!$row) {
            return [
                'state' => false,
                'error' => self::$errors['row_not_exist']
            ];
        }

        $row->getConnection()->beginTransaction();
        try {
            foreach (self::$fields as $v) {
                if (isset($params[$v])) {
                    $row->$v = $params[$v];
                }
            }

            $update = $row->save();
            if (!$update) {
                throw new \Exception(self::$errors['save_error']);
            }

            $row->getConnection()->commit();
            $send = [
                'state' => true,
                'data' => $row
            ];
        } catch (\Exception $e) {
            $row->getConnection()->rollBack();
            $send = [
                'state' => false,
                'error' => $e->getMessage()
            ];
        }

        return $send;
    }
}