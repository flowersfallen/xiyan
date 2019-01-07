<?php

namespace App\Http\Middleware;

use Closure;

class CrossHttp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // 调试模式支持跨域
        if (config('app.debug')) {
            $response->header('Access-Control-Allow-Origin', 'http://localhost:8080');
            $response->header('Access-Control-Allow-Credentials', 'true');
            $strTooLong = 'Origin, X-Token, Content-Type, Accept, Authorization, SiteId';
            $response->header('Access-Control-Allow-Headers', $strTooLong);
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS, DELETE');
        }

        return $response;
    }
}
