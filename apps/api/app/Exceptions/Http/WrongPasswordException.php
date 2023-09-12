<?php

namespace App\Exceptions\Http;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class WrongPasswordException extends AccessDeniedHttpException
{
    public function __construct($message = 'wrong credential', \Exception $previous = null)
    {
        parent::__construct(
            $message,
            $previous,
            AuthErrorCodes::WRONG_PASSWORD
        );
    }
}
