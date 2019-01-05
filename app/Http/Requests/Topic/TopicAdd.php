<?php

namespace App\Http\Requests\Topic;

use App\Http\Requests\BaseRequest;

class TopicAdd extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '标题必需.',
            'description.required' => '描述必需.'
        ];
    }
}
