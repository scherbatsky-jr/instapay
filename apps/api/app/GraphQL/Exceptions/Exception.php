<?php

namespace App\GraphQL\Exceptions;

use Exception as BaseException;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

class Exception extends BaseException implements RendersErrorsExtensions
{
    protected $code;

    public function __construct(string $message, string $code)
    {
        parent::__construct($message);

        $this->code = $code;
    }

    /**
     * Return the content that is put in the "extensions" part
     * of the returned error.
     */
    public function extensionsContent(): array
    {
        return [
            'code' => $this->code,
        ];
    }

    public function getCategory(): string
    {
        return 'server_error';
    }

    public function isClientSafe(): bool
    {
        return true;
    }
}
