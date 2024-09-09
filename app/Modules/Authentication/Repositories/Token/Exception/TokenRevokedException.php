<?php

namespace App\Modules\Authentication\Repositories\Token\Exception;

class TokenRevokedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Token revoked");
    }
}
