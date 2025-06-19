<?php

namespace App\Exceptions;

class AuthenticationException extends ClientException
{
    protected string $name = 'AuthenticationException';

    public function __construct($message = 'Authentication Exception', $code = 401, $status = 401)
    {
        parent::__construct($message, $code, $status);
    }
}
