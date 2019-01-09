<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class FollowId extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'follow_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'follow_id.required' => '对应用户id必需.',
        ];
    }
}
