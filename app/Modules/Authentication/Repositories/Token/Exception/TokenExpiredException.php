<?php

namespace App\Modules\Authentication\Repositories\Token\Exception;

class TokenExpiredException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Token expired");
    }
}
