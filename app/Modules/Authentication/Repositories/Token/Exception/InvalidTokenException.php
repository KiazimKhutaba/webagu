<?php

namespace App\Modules\Authentication\Repositories\Token\Exception;

class InvalidTokenException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Invalid token: $message");
    }
}
