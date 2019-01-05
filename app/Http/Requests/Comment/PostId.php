<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\BaseRequest;

class PostId extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'post_id.required' => '帖子id必需.',
        ];
    }
}
