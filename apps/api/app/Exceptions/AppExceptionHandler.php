<?php

namespace App\Exceptions;

use App\Exceptions\Http\InvalidCredentialsException;
use GraphQL\Error\Error;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Nuwave\Lighthouse\Execution\ErrorHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppExceptionHandler implements ErrorHandler
{
    public function __invoke(?Error $error, \Closure $next): ?array
    {
        $underlyingException = $error->getPrevious();

        if ($underlyingException instanceof AppException) {
            $error = new Error(
                $underlyingException->getMessage(),
                $error->nodes,
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                $underlyingException,
                [
                    'error' => [
                        'code' => $underlyingException->getErrorCode(),
                        'message' => $underlyingException->getMessage(),
                    ],
                ]
            );
        }

        if ($underlyingException instanceof InvalidCredentialsException) {
            $error = new Error(
                $underlyingException->getMessage(),
                $error->nodes,
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                $underlyingException,
                [
                    'error' => [
                        'code' => 'invalid_credentials',
                        'message' => 'Username or email is not correct.',
                    ],
                ]
            );
        }

        if ($underlyingException instanceof ThrottleRequestsException) {
            $error = new Error(
                $underlyingException->getMessage(),
                $error->nodes,
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                $underlyingException,
                [
                    'error' => [
                        'code' => 'too_many_attempts',
                        'message' => 'Too many attempts.',
                    ],
                ]
            );
        }

        if ($underlyingException instanceof NotFoundHttpException) {
            $error = new Error(
                $underlyingException->getMessage(),
                $error->nodes,
                $error->getSource(),
                $error->getPositions(),
                $error->getPath(),
                $underlyingException,
                [
                    'error' => [
                        'code' => 'resource_not_found',
                        'message' => 'Resource not found.',
                    ],
                ]
            );
        }

        return $next($error);
    }
}
