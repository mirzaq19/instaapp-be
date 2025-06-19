<?php

namespace App\Exceptions;

use Exception;

class AuthorizationException extends Exception
{
    protected string $name = 'AuthorizationException';

    public function __construct($message = 'Authorization Exception', $code = 403, $status = 403)
    {
        parent::__construct($message, $code, $status);
    }
}
