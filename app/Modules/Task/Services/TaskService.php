<?php

namespace App\Modules\Task\Services;

use App\Common\Enums\FileableType;
use App\Common\Files\FileUploadService;
use App\Modules\Authentication\Services\AuthenticatedUser;
use App\Modules\Task\Dtos\StudentAnswerDto;
use App\Modules\Task\Dtos\TaskCreateDto;
use App\Modules\Task\Dtos\TaskReviewDto;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Repositories\TasksRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Throwable;

class TaskService
{

    public function __construct(
        private readonly FileUploadService $fileUploadService,
        private readonly TasksRepository   $tasksRepository,
    )
    {
    }

    public function createTask(TaskCreateDto $dto)
    {
        return Task::create($dto->toArray());
    }


    public function getStudentTask($task_id, $student_id, AuthenticatedUser $user)
    {
        $relations = $user->isAdmin() || $user->isTeacher() ? ['students'] : [];
        return $this->tasksRepository->getStudentTask($task_id, $student_id, $relations);
    }


    /**
     * This what admin or teacher do - save review for task
     *
     * @throws Throwable
     */
    public function addTeacherReview(TaskReviewDto $reviewDto, AuthenticatedUser $user)
    {
        return $this->tasksRepository->addTeacherReview($reviewDto, $user);
    }

    /**
     * This method updates task history attributes: status and others....
     *
     * @param StudentAnswerDto $dto
     * @param AuthenticatedUser $user
     * @return array
     * @throws Throwable
     */
    public function addStudentAnswer(StudentAnswerDto $dto, AuthenticatedUser $user): array
    {
        $history = $this->tasksRepository->addStudentAnswer($dto, $user);
        $files = $dto->getFiles();
        $has_files = count($files) > 0;

        if ($has_files) {
            $this->uploadFiles($files, $user->id, $history->id);
        }

        return [
            ...$history->toArray(),
            'user' => $history->user()->first(),
            'files' => $has_files ? $history->files()->get() : [],
        ];
    }

    /**
     * @param array|UploadedFile[] $files
     * @param int $user_id
     * @param int $history_item_id
     * @return void
     */
    private function uploadFiles(array $files, int $user_id, int $history_item_id): void
    {
        foreach ($files as $file) {
            $this->fileUploadService->upload(
                $file, $user_id,
                fileable_type: FileableType::TaskHistory->value,
                fileable_id: $history_item_id
            );
        }
    }


    public function getStudentTasks(int $student_id, int $lecture_id = 0, array $fields = ['*'])
    {
        return $this->tasksRepository
            ->getStudentTasks($student_id, $lecture_id)
            ->paginate(columns: $fields);
    }

    public function getStudentTasksWithStatuses(int $student_id, int $lecture_id = 0, array $fields = ['*'])
    {
        return $this->tasksRepository
            ->getStudentTasksWithStatuses($student_id, $lecture_id)
            ->paginate(columns: $fields);
    }


    public function getStudentTaskHistory(int $task_id, int $student_id)
    {
        return $this->tasksRepository->getStudentTaskHistory($task_id, $student_id);
    }

    public function getListOfTasks(array $fields = ['*'], int $lecture_id = 0): Builder|Task|\Illuminate\Database\Query\Builder
    {
        return $this->tasksRepository->getListOfTasks($fields, $lecture_id);
    }
}
