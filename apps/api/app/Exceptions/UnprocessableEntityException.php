<?php

namespace App\Exceptions;

class UnprocessableEntityException extends AppException
{
    public function __construct($message, $errorCode)
    {
        parent::__construct(
            $message,
            $errorCode,
            '422'
        );
    }
}
