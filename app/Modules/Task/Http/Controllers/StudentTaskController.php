<?php

namespace App\Modules\Task\Http\Controllers;

use App\Common\UserRole;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Task\Dtos\StudentAnswerDto;
use App\Modules\Task\Http\Requests\UpdateTaskHistoryRequest;
use App\Modules\Task\Http\Requests\UserTasksColumnsRequest;
use App\Modules\Task\Services\TaskService;
use Illuminate\Pagination\Paginator;

class StudentTaskController extends Controller
{

    public function __construct(
        private readonly AuthenticationService $authenticationService,
        private readonly TaskService $taskService,
    )
    {
    }


    /**
     * @throws \Throwable
     */
    public function sendForReview(UpdateTaskHistoryRequest $historyRequest)
    {
        $data = array_merge($historyRequest->validated(), [
            'files' => $historyRequest->file('files')
        ]);


        $user = $this->authenticationService->buildAuthenticatedUser(auth()->id(), UserRole::Student);

        return $this->taskService->addStudentAnswer(StudentAnswerDto::from($data), $user);
    }


    public function getStudentTasksWithStatuses(UserTasksColumnsRequest $request)
    {
        Paginator::currentPathResolver(fn() => '/tasks');
        $fields = $request->validated('fields', ['*']);
        $lecture_id = intval($request->get('lecture_id', 0));

        return $this->taskService->getStudentTasksWithStatuses(auth()->id(), $lecture_id, $fields);
    }
}
