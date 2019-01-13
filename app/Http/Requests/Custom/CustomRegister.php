<?php

namespace App\Http\Requests\Custom;

use App\Http\Requests\BaseRequest;

class CustomRegister extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'code' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '昵称必填.',
            'email.required' => '邮箱必填.',
            'email.email' => '邮箱格式不对.',
            'password.required' => '密码必填.',
            'code.required' => '邀请码必填.',
        ];
    }
}
