<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
        if ($exception instanceof BadRequestHttpException && $request->is('api/*')) {
            return response()->json([
                'error' => '400',
                'message' => 'Bad Request.'
            ], 400);
        }
        if ($exception instanceof ModelNotFoundException && $request->is('api/*')) {
            return response()->json([
                'error' => '404',
                'message' => 'Resource item not found.'
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException && $request->is('api/*')) {
            return response()->json([
                'error' => '404',
                'message' => 'Resource not found.'
            ], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException && $request->is('api/*')) {
            return response()->json([
                'error' => '405',
                'message' => 'Method not allowed.'
            ], 405);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json(
                [
                    'error' => '401',
                    'message' => 'Unauthorized',
                ],
                401
            );
        }
        return parent::render($request, $exception);
    }
}
