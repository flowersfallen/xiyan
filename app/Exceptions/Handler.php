<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Traits\ResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->formatReturn(
                [
                    'state' => false,
                    'error' => '请求方式有误'
                ]
            );
        }

        if ($exception instanceof BadRequestHttpException) {
            return $this->formatReturn(
                [
                    'state' => false,
                    'error' => $exception->getMessage()
                ]
            );
        }

        if ($exception instanceof AuthenticationException) {
            return $this->formatReturn(
                [
                    'state' => false,
                    'error' => '用户认证失败',
                    'message' => 'login'
                ]
            );
        }

        if ($exception instanceof TokenBlacklistedException) {
            return $this->formatReturn(
                [
                    'state' => false,
                    'error' => 'token失效',
                    'message' => 'login'
                ]
            );
        }

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }
        $msg = '';
        foreach ($e->errors() as $errors) {
            foreach ($errors as $error) {
                $msg .= $error;
            }
        }

        return $this->formatReturn([
            'state' => false,
            'error' => $msg
        ]);
    }
}
