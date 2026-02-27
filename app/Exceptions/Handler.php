<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

    // 1. Error kon ang Username exists na o kulang ang input (Validation)
    if ($exception instanceof ValidationException) {
        return response()->json([
            'error' => 'Validation Error',
            'messages' => $exception->errors()
        ], 422);
    }

    // 2. Error kon ang User ID wala sa database (User Not Found)
    if ($exception instanceof ModelNotFoundException) {
        return response()->json([
            'error' => 'User not found',
            'message' => 'Ang ID nga imong gipangita wala sa among database.'
        ], 404);
    }

    // 3. General HTTP errors (like 405 Method Not Allowed)
    if ($exception instanceof HttpException) {
        return response()->json([
            'error' => 'HTTP Error',
            'message' => $exception->getMessage()
        ], $exception->getStatusCode());
    }

    // Default behavior for other errors
    return parent::render($request, $exception);
        
    }
}
