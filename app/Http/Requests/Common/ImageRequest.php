<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\BaseRequest;

class ImageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'image不能为空.',
            'image.image' => 'image需为图片文件.',
        ];
    }
}
