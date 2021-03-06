<?php

namespace App\Http\Requests\Custom;

use App\Http\Requests\BaseRequest;

class CustomLogin extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '邮箱必填.',
            'email.email' => '邮箱格式不对.',
            'password.required' => '密码必填.',
        ];
    }
}
