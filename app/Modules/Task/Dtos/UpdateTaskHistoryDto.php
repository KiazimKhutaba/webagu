<?php

namespace App\Modules\Task\Dtos;

use App\Modules\Task\Enums\TaskStatus;

class UpdateTaskHistoryDto
{
    private int $lecture_id;
    private int $receiver_id;


    public function __construct(
        private int        $task_id,
        private int        $sender_id,
        private TaskStatus $task_status,
        private ?string $message,
        private ?array $files = [],
    )
    {
    }


    public static function from(array $data): self
    {
        return new self(
            task_id: $data['task_id'],
            sender_id: $data['sender_id'],
            task_status: TaskStatus::from($data['status']),
            message: $data['message'] ?? '',
            files: $data['files'] ?? []
        );
    }


    public function toArray(): array
    {
        return [
            'task_id' => $this->task_id,
            'sender_id' => $this->sender_id,
            'status' => $this->task_status->value,
            'lecture_id' => $this->lecture_id,
            'receiver_id' => $this->receiver_id,
            'message' => $this->message,
            'files' => $this->files
        ];
    }

    public function getTaskId(): int
    {
        return $this->task_id;
    }

    public function setTaskId(int $task_id): UpdateTaskHistoryDto
    {
        $this->task_id = $task_id;
        return $this;
    }

    public function getSenderId(): int
    {
        return $this->sender_id;
    }

    public function setSenderId(int $sender_id): UpdateTaskHistoryDto
    {
        $this->sender_id = $sender_id;
        return $this;
    }

    public function getTaskStatus(): TaskStatus
    {
        return $this->task_status;
    }

    public function setTaskStatus(TaskStatus $task_status): UpdateTaskHistoryDto
    {
        $this->task_status = $task_status;
        return $this;
    }

    public function getLectureId(): int
    {
        return $this->lecture_id;
    }

    public function setLectureId(int $lecture_id): UpdateTaskHistoryDto
    {
        $this->lecture_id = $lecture_id;
        return $this;
    }

    public function getReceiverId(): int
    {
        return $this->receiver_id;
    }

    public function setReceiverId(int $receiver_id): UpdateTaskHistoryDto
    {
        $this->receiver_id = $receiver_id;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): UpdateTaskHistoryDto
    {
        $this->message = $message;
        return $this;
    }

    public function getFiles(): ?array
    {
        return $this->files;
    }

    public function setFiles(?array $files): UpdateTaskHistoryDto
    {
        $this->files = $files;
        return $this;
    }


}
