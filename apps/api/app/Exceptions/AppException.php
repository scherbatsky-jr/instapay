<?php

namespace App\Exceptions;

use Exception as GlobalException;

class AppException extends GlobalException
{
    protected $errorCode;

    public function __construct($message, $errorCode, $code = 0, \Throwable $previous = null)
    {
        $this->errorCode = $errorCode;

        parent::__construct($message, $code, $previous);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
