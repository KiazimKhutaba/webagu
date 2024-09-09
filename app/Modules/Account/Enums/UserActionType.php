<?php

namespace App\Modules\Account\Enums;

enum UserActionType: string
{
    case Login = 'login';
    case Logout = 'logout';
    case Register = 'register';
}
