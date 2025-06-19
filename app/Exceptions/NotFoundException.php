<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected string $name = 'NotFoundException';

    public function __construct($message = 'Not Found Exception', $code = 404, $status = 404)
    {
        parent::__construct($message, $code, $status);
    }
}
