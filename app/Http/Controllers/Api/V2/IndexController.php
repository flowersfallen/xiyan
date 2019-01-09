<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Common\FileRequest;

class IndexController extends BaseController
{
    public function file(FileRequest $request)
    {
        $path = $request->file('file')->store('public/'.date('Ymd'));

        return $this->formatReturn([
            'state' => true,
            'data' => [
                'path' => $path,
                'url' =>  path_url($path)
            ]
        ]);
    }
}