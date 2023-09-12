<?php

namespace App\Exceptions\Http;

class AuthErrorCodes
{
    public const INVALID_CREDENTIALS = 969;
    public const INVALID_TOKEN = 970;

    public const UNAUTHORIZED = 401;

    public const USER_ALREADY_DISABLED = 996;
    public const USER_ALREADY_ENABLED = 997;
    public const USER_ALREADY_EXISTS = 998;
    public const USER_IS_DISABLED = 995;
    public const USER_NOT_FOUND = 999;

    public const WRONG_PASSWORD = 968;
}
