<?php

namespace App\Modules\Task\Dtos;

class TaskReviewDto
{
    public function __construct(
        private int    $task_id,
        private int    $student_id,
        private string $message,
        private string $status,
        private array  $files = []
    )
    {
    }

    public static function from(array $data): self
    {
        return new self(
            $data['task_id'],
            $data['student_id'],
            $data['message'] ?? '',
            $data['status'],
        );
    }

    public function toArray(): array
    {
        return [
            'task_id' => $this->task_id,
            'student_id' => $this->student_id,
            'message' => $this->message,
            'status' => $this->status,
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

    public function getStudentId(): int
    {
        return $this->student_id;
    }

    public function setStudentId(int $student_id): self
    {
        $this->student_id = $student_id;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}
