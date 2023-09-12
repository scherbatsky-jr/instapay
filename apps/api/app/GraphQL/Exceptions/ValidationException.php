<?php

namespace App\GraphQL\Exceptions;

use Illuminate\Validation\ValidationException as BaseException;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

class ValidationException extends BaseException implements RendersErrorsExtensions
{
    public function extensionsContent(): array
    {
        return ['validation' => $this->errors()];
    }

    public function getCategory()
    {
        return 'validation';
    }

    public function isClientSafe()
    {
        return true;
    }
}
