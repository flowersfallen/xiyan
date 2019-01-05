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
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '账号必需.',
            'password.required' => '密码必需.',
        ];
    }
}
