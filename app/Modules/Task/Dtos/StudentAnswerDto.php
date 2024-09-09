<?php

namespace App\Modules\Task\Dtos;

class StudentAnswerDto
{
    public function __construct(
        private int    $task_id,
        private string $message = "",
        private ?array  $files = []
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            $data['task_id'],
            $data['message'],
            $data['files'] ?? [],
        );
    }


    public function toArray(): array
    {
        return [
            'task_id' => $this->task_id,
            'message' => $this->message,
            'files' => $this->files,
        ];
    }

    public function getTaskId(): int
    {
        return $this->task_id;
    }

    public function setTaskId(int $task_id): self
    {
        $this->task_id = $task_id;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): self
    {
        $this->files = $files;
        return $this;
    }

}
