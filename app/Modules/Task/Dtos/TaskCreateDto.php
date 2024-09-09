<?php

namespace App\Modules\Task\Dtos;

class TaskCreateDto
{

    public function __construct(
        public readonly string $title,
        public readonly int $lecture_id,
        public readonly string $description,
        public readonly bool $is_visible,
        public readonly int $user_id,
    )
    {
    }


    public static function from(array $data): self
    {
        return new self(
            title: $data['title'],
            lecture_id: $data['lecture_id'],
            description: $data['description'],
            is_visible: intval($data['is_visible']),
            user_id: $data['user_id'],
        );
    }


    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'lecture_id' => $this->lecture_id,
            'description' => $this->description,
            'is_visible' => $this->is_visible,
            'user_id' => $this->user_id,
        ];
    }
}
