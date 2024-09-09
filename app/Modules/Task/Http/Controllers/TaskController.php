<?php

namespace App\Modules\Task\Http\Controllers;

use App\Common\UserRole;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Task\Dtos\TaskCreateDto;
use App\Modules\Task\Dtos\TaskReviewDto;
use App\Modules\Task\Http\Requests\CreateTaskRequest;
use App\Modules\Task\Http\Requests\UpdateTaskHistoryRequest;
use App\Modules\Task\Http\Requests\UserTasksColumnsRequest;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Services\TaskService;
use Illuminate\Database\Eloquent\Model;

class TaskController extends Controller
{

    public function __construct(
        private readonly TaskService $taskService,
        private readonly AuthenticationService $authenticationService,
    )
    {
    }


    public function getListOfTasks(UserTasksColumnsRequest $request)
    {
        $fields = $request->validated('fields', ['*']);
        $lecture_id = intval($request->get('lecture_id', 0));

        return $this->taskService->getListOfTasks($fields, $lecture_id)->paginate();
    }


    public function addTeacherReview(UpdateTaskHistoryRequest $historyRequest)
    {
        $data = array_merge($historyRequest->validated(), [
            'sender_id' => auth()->id()
        ]);

        $user = $this->authenticationService->buildAuthenticatedUser(auth()->id(), UserRole::Admin);

        return $this->taskService->addTeacherReview(TaskReviewDto::from($data), $user);
    }


    public function getStudentTask(int $task_id)
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());
        return $this->taskService->getStudentTask($task_id, auth()->id(), $user);
    }


    public function createTask(CreateTaskRequest $request): Model|Task
    {
        $data = array_merge($request->validated(), [
            'user_id' => auth()->id()
        ]);

        return $this->taskService->createTask(TaskCreateDto::from($data));
    }
}
