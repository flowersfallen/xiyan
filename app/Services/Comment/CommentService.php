<?php

namespace App\Services\Comment;

use App\Services\BaseService;
use App\Models\Comment\Comment;
use App\Services\Post\PostService;

class CommentService extends BaseService
{
    protected static $fields = [
        'post_id',
        'content',
        'created_from',
        'created_by',
        'status',
    ];

    protected static $errors = [
        'save_error' => '保存失败',
        'row_not_exist' => '未查到对应记录',
        'no_privilege' => '没有权限',
    ];

    public function commentAdd($params)
    {
        $post = new PostService();
        $record = new Comment();
        $record->getConnection()->beginTransaction();
        try {
            foreach (self::$fields as $v) {
                if (isset($params[$v])) {
                    $record->$v = $params[$v];
                }
            }

            $insert = $record->save();
            if (!$insert) {
                throw new \Exception(self::$errors['save_error']);
            }

            $res = $post->postInteract([
                'user_id' => $params['created_by'],
                'id' => $params['post_id'],
                'type' => 'comment'
            ]);
            if (!$res['state']) {
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

    public function commentList($params, $front = 0)
    {
        $keyword = isset($params['keyword']) ? $params['keyword'] : false;
        $pagesize = isset($params['pagesize']) ? $params['pagesize'] : 15;

        if (!$front) {
            $where = [
                ['status', '<>', 3],
                ['post_id', '=', $params['post_id']]
            ];
        } else {
            $where = [
                ['status', '=', 1],
                ['post_id', '=', $params['post_id']]
            ];
        }

        $rows = Comment::query()->select('comments.id', 'comments.post_id', 'comments.content', 'comments.created_at', 'users.name', 'users.avatar')
            ->leftJoin('users', 'comments.created_by', '=', 'users.id')
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

    public function commentDetail($params)
    {
        $row = Comment::query()
            ->where([
                ['comments.status', '<>', 3],
                ['comments.id', '=', $params['id']],
            ])
            ->first();

        $send = [
            'state' => true,
            'data' => $row ? $row : []
        ];

        return $send;
    }

    public function commentUpdate($params)
    {
        $row = Comment::query()->where([
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