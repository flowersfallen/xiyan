<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\BaseRequest;

class CommentId extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '评论id必需.',
        ];
    }
}
