<?php

namespace App\Common\Helpers;

class ArrayHelper
{
    public static function removeNullable(array $array): array
    {
        // ['source' => $array, 'filtered' => array_filter($array, $callback)];
        return array_filter($array, self::is_not_null(...));
    }

    private static function is_not_null(mixed $value): bool
    {
        return !is_null($value);
    }
}
