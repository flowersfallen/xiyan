<?php

namespace App\Http\Requests\Common;

use App\Http\Requests\BaseRequest;

class FileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'file不能为空.',
            'file.file' => 'file需为文件.',
        ];
    }
}
