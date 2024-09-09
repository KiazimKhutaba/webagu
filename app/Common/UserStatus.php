<?php

namespace App\Common;

enum UserStatus : int
{
    case Activated = 1;
    case NotActivated = 0;

    public function name(): string
    {
        return match ($this) {
            self::Activated => 'activated',
            self::NotActivated => 'not_activated'
        };
    }
}
