<?php

namespace App\Modules\Task\Dtos;

class TaskHistoryDto
{

    public function __construct(
        private readonly ?int $id,
        private readonly string $text,

    )
    {
    }
}
