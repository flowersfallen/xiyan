<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
            if ($request instanceof SymfonyResponse) {
                $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:8080');
                $strTooLong = 'Origin, Content-Type, Accept, Authorization';
                $response->headers->set('Access-Control-Allow-Headers', $strTooLong);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            } else {
                $response->header('Access-Control-Allow-Origin', 'http://localhost:8080');
                $strTooLong = 'Origin, Content-Type, Accept, Authorization';
                $response->header('Access-Control-Allow-Headers', $strTooLong);
                $response->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            }
        }

        return $response;
    }
}
