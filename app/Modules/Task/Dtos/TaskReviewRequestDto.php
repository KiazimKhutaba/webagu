<?php

namespace App\Modules\Task\Dtos;

use App\Common\Dtos\RequestDtoInterface;

class TaskReviewRequestDto implements RequestDtoInterface
{

    public function __construct
    (
        public readonly int $lectureId,
        public readonly int $taskId,
        public readonly string $reviewStatus,
        public readonly int $studentId,
        public readonly int $reviewerId
    )
    {
    }

    public static function from(array $input): self
    {
        return new self($input['lecture_id'], $input['task_id'], $input['review_status'], $input['student_id'], $input['reviewer_id']);
    }

    public function rules(): array
    {
        return [];
    }

    public function toArray(): array
    {
        return [
            'lecture_id' => $this->lectureId,
            'task_id' => $this->taskId,
            'review_status' => $this->reviewStatus,
            'student_id' => $this->studentId,
            'reviewer_id' => $this->reviewerId
        ];
    }
}
