<?php

namespace App\Exceptions;

use App\Exceptions\Http\InvalidCredentialsException;
use App\Exceptions\Http\UserNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (\Throwable $e) {
        });
    }

    public function render($request, \Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'error' => [
                    'code' => 'resource_not_found',
                    'message' => 'Resource not found.',
                ],
            ], 404);
        }

        if ($e instanceof ThrottleRequestsException) {
            return response()->json([
                'error' => [
                    'code' => 'too_many_attempts',
                    'message' => 'Too many attempts',
                ],
            ], 429);
        }

        if ($e instanceof InvalidCredentialsException) {
            return response()->json([
                'error' => [
                    'code' => 'invalid_credentials',
                    'message' => 'Invalid credentials',
                ],
            ], 401);
        }

        if ($e instanceof UserNotFoundException) {
            return response()->json([
                'error' => [
                    'code' => 'user_not_found',
                    'message' => 'User not found',
                ],
            ], 404);
        }

        if ($e instanceof AppException) {
            return response()->json([
                'error' => [
                    'code' => $e->getErrorCode(),
                    'message' => $e->getMessage(),
                ],
            ], $e->getCode());
        }

        return parent::render($request, $e);
    }
}
