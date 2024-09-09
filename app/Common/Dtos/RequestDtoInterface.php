<?php

namespace App\Common\Dtos;

interface RequestDtoInterface
{
    public static function from(array $input): self;

    public function rules(): array;

    public function toArray(): array;
}
