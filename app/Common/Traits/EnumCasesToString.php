<?php

namespace App\Common\Traits;

trait EnumCasesToString
{
    public static function casesAsString():string {
        return  implode(',', array_map(fn($case) => $case->value, self::cases()));
    }
}
