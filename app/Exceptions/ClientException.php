<?php

namespace App\Exceptions;

use Exception;

class ClientException extends Exception
{
    protected int $status;
    protected string $name = 'ClientException';

    public function __construct($message = 'Client Exception', $code = 400, $status = 400)
    {
        parent::__construct($message, $code);
        $this->status = $status;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
