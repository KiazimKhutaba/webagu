<?php

namespace App\Common\Enums;


use App\Common\Traits\EnumCasesToString;

enum UserRole: string
{
    use EnumCasesToString;

    case Admin = 'admin';
    case Teacher = 'teacher';
    case Student = 'user';
}
