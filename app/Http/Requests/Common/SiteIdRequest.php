<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\BaseRequest;

class SiteIdRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'site_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'site_id.required' => '站点id不能为空.',
            'site_id.numeric' => '站点id需为数字.',
        ];
    }
}
