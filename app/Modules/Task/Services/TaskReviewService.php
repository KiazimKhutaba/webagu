<?php

namespace App\Modules\Task\Services;

use App\Modules\Task\Dtos\TaskReviewRequestDto;
use App\Modules\Task\Models\Review;
use App\Modules\Task\Repositories\TasksRepository;
use JetBrains\PhpStorm\Deprecated;


class TaskReviewService
{
    public function __construct(
        private readonly TasksRepository $tasksRepository
    )
    {
    }

    #[Deprecated]
    public function save(TaskReviewRequestDto $dto)
    {
        $review = Review::where(['task_id' => $dto->taskId, 'student_id' => $dto->studentId]);

        if($review->exists()) {
            return $review->update($dto->toArray());
        }

        return $review->create($dto->toArray());
    }

    public function getTasksSentByStudents(int $lecture_id, int $task_id)
    {
        return $this->tasksRepository->getTasksSentByStudents($lecture_id, $task_id);
    }

    public function latestReview(int $task_id, int $student_id)
    {
        return $this->tasksRepository->getStudentTaskCurrentStatus($task_id, $student_id);
    }
}
