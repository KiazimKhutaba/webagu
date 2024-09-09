<?php

namespace App\Common\Traits;

trait ToJson
{
    public function toJson(mixed $data = null): string
    {
        return json_encode($data ?: $this, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}