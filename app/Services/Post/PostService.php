<?php

namespace App\Services\Post;

use App\Services\BaseService;
use App\Models\Post\Post;
use App\Models\Post\Interact;
use App\Models\Notice\Notice;

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
        'comment_audit'
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

    public function postInteract($params)
    {
        $title = '';
        $message = '';
        $from = $params['user_id'];
        $to = '';

        $record = new Interact();
        $record->getConnection()->beginTransaction();
        try {
            $post = Post::query()->where([
                ['id', '=', $params['id']],
                ['status', '=', 1]
            ])->first();
            if (!$post) {
                throw new \Exception(self::$errors['row_not_exist']);
            }
            $to = $post->created_by;

            $row = Interact::query()->where([
                ['post_id', '=', $params['id']],
                ['type', '=', $params['type']],
                ['user_id', '=', $params['user_id']]
            ])->first();

            if ($row && $params['type'] == 'digg') {
                // 点赞再点删除
                $update = $row->delete();
                if (!$update) {
                    throw new \Exception(self::$errors['save_error']);
                }

                $update = $this->interactPost($params['id'], $params['type'], -1);
                if (!$update) {
                    throw new \Exception(self::$errors['save_error']);
                }

                $title = '点赞';
                $message = '取消点赞';
            } else {
                // 同种交互多次
                $update = $this->interactAdd($params['id'], $params['type'], $params['user_id']);
                if (!$update) {
                    throw new \Exception(self::$errors['save_error']);
                }

                $update = $this->interactPost($params['id'], $params['type'], 1);
                if (!$update) {
                    throw new \Exception(self::$errors['save_error']);
                }
                switch ($params['type']) {
                    case 'digg':
                        $title = '点赞';
                        $message = '新增点赞';
                        break;
                    case 'comment':
                        $title = '评论';
                        $message = '新增评论';
                        break;
                    case 'share':
                        $title = '分享';
                        $message = '新增分享';
                        break;
                }
            }

            // 关联消息
            $time = date('Y-m-d H:i:s');
            $relate = [
                'from' => $from,
                'to' => $to,
                'title' => $title,
                'message' => $message,
                'state' => 1,
                'created_at' => $time,
                'updated_at' => $time
            ];
            $insert = Notice::query()->insert($relate);
            if (!$insert) {
                throw new \Exception(self::$errors['save_error']);
            }

            $record->getConnection()->commit();
            $send = [
                'state' => true
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

    public function interactAdd($post_id, $type, $user_id)
    {
        $time = date('Y-m-d H:i:s');
        $update = Interact::query()->insert([
            'post_id' => $post_id,
            'type' => $type,
            'user_id' => $user_id,
            'created_at' => $time,
            'updated_at' => $time
        ]);
        if (!$update) {
            return false;
        }

        return true;
    }

    public function interactPost($post_id, $type, $add)
    {
        $row = Post::query()->where([
            ['id', '=', $post_id]
        ])->first();

        if (!$row) {
            return false;
        }

        $row->$type += $add;
        $update = $row->save();
        if (!$update) {
            return false;
        }

        return true;
    }
}