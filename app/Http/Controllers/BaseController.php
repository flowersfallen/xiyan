<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    public function formatReturn(array $params)
    {
        if (!isset($params['message'])) {
            $params['message'] = '';
        }
        if (isset($params['state']) && $params['state'] === true && !isset($params['data'])) {
            $params['data'] = [];
        }
        return response()->json($params);
    }
}
