<?php

namespace App\Http\Requests\Post;

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
            'id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '帖子id必需.',
        ];
    }
}
