<?php

namespace App\Exceptions;

class AuthorizationException extends ClientException
{
    protected string $name = 'AuthorizationException';

    public function __construct($message = 'Authorization Exception', $code = 403, $status = 403)
    {
        parent::__construct($message, $code, $status);
    }
}
