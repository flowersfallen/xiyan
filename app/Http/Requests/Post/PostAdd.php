<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseRequest;

class PostAdd extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'topic_id'=> 'required',
            'content' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'topic_id.required' => '话题id必需.',
            'content.required' => '内容必需.'
        ];
    }
}
