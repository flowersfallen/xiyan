<?php

namespace App\Services\Post;

use App\Services\BaseService;
use App\Models\Post\Post;

class PostService extends BaseService
{
    protected static $fields = [
        'topic_id',
        'content',
        'attachment',
        'type',
        'created_from',
        'created_by',
        'status',
    ];

    protected static $errors = [
        'save_error' => '保存失败',
        'row_not_exist' => '未查到对应记录',
        'no_privilege' => '没有权限',
    ];

    public function postAdd($params)
    {
        $record = new Post();
        $record->getConnection()->beginTransaction();
        try {
            foreach (self::$fields as $v) {
                if (isset($params[$v])) {
                    $record->$v = $params[$v];
                }
            }
            $record->status = 1;
            $record->comment_audit = 1;

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

    public function postList($params, $front = 0)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : false;
        $pagesize = isset($params['pagesize']) ? $params['pagesize'] : 15;

        if (!$front) {
            $where = [
                ['status', '<>', 3],
                ['topic_id', '=', $params['topic_id']]
            ];
        } else {
            $where = [
                ['status', '=', 1]
            ];
            if (isset($params['topic_id'])) {
                $where[] = ['topic_id', '=', $params['topic_id']];
            }
        }

        $rows = Post::query()->select('posts.id', 'posts.topic_id', 'posts.content', 'posts.attachment','posts.digg', 'posts.comment', 'posts.created_at', 'users.name', 'users.avatar')
            ->leftJoin('users', 'posts.created_by', '=', 'users.id')
            ->where($where)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('content', 'like', '%' . $keyword . '%');
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

    public function postDetail($params)
    {
        $row = Post::query()->select('posts.id', 'posts.topic_id', 'posts.content', 'posts.attachment','posts.digg', 'posts.comment', 'posts.created_at', 'users.name', 'users.avatar', 'topics.title')
            ->leftJoin('users', 'posts.created_by', '=', 'users.id')
            ->leftJoin('topics', 'posts.topic_id', '=', 'topics.id')
            ->where([
                ['posts.status', '<>', 3],
                ['posts.id', '=', $params['id']],
            ])
            ->first();

        $send = [
            'state' => true,
            'data' => $row ? $row : []
        ];

        return $send;
    }

    public function postUpdate($params)
    {
        $row = Post::query()->where([
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