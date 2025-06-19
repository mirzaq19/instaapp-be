<?php

namespace App\Exceptions;


class InvariantException extends InvariantException
{
    protected string $name = 'InvariantException';

    public function __construct($message = 'Invariant Exception', $code = 400, $status = 400)
    {
        parent::__construct($message, $code, $status);
    }
}
