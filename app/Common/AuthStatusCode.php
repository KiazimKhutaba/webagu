<?php

namespace App\Common;

enum AuthStatusCode: int
{
    case LoginOrPasswordIncorrect = 0x100;
    case UserNotActivated = 0x101;
}
