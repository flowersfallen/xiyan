<?php

namespace App\Services\User;

use App\Services\BaseService;
use App\Models\Friend\Friend;
use App\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserService extends BaseService
{
    public function checkFriend($user_id, $follow_id)
    {
        $row = Friend::query()->select('id', 'user_id', 'follow_id')
            ->where([
                ['user_id', '=', $user_id],
                ['follow_id', '=', $follow_id]
            ])
            ->first();
        return $row;
    }

    public function getMember($user_id)
    {
        $row = User::query()->select('id')->where('id', $user_id)->first();
        return $row;
    }

    public function userFollow($params)
    {
        if ($params['user_id'] == $params['follow_id']) {
            throw new BadRequestHttpException('不可添加自己为好友.');
        }

        $row = $this->checkFriend($params['user_id'], $params['follow_id']);
        if ($row) {
            return [
                'state' => true
            ];
        }

        $row = $this->getMember($params['follow_id']);
        if (!$row) {
            throw new BadRequestHttpException('对应用户不存在.');
        }

        $time = date('Y-m-d H:i:s');
        $update = Friend::query()->insert([
            'user_id' => $params['user_id'],
            'follow_id' => $params['follow_id'],
            'created_at' => $time,
            'updated_at' => $time
        ]);

        if (!$update) {
            throw new BadRequestHttpException('添加失败.');
        }

        return [
            'state' => true
        ];
    }

    public function userUnfollow($params)
    {
        $row = $this->checkFriend($params['user_id'], $params['follow_id']);
        if (!$row) {
            throw new BadRequestHttpException('非好友关系,删除失败.');
        }

        $update = $row->delete();

        if (!$update) {
            throw new BadRequestHttpException('删除失败.');
        }

        return [
            'state' => true
        ];
    }
}