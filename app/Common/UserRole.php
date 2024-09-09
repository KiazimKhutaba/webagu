<?php

namespace App\Common;


use App\Common\Traits\EnumCasesToString;

enum UserRole: int
{
    use EnumCasesToString;

    case Admin = 1;
    case Teacher = 2;
    case Student = 3;

    public function name(): string
    {
        return match ($this) {
            self::Admin => 'admin',
            self::Teacher => 'teacher',
            self::Student => 'user',
        };
    }

    public static function fromString(string $value): self
    {
        return match ($value) {
            'admin' => self::Admin,
            'teacher' => self::Teacher,
            'user' => self::Student,
        };
    }
}
