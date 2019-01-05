<?php

namespace App\Http\Requests\Topic;

use App\Http\Requests\BaseRequest;

class TopicId extends BaseRequest
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
            'id.required' => '话题id必需.',
        ];
    }
}
