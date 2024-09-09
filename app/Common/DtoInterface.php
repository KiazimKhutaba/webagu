<?php

namespace App\Common;

interface DtoInterface
{
    public static function from(array $data): static;

    public function toArray(): array;
}
