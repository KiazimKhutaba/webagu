<?php

namespace App\Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Task\Dtos\TaskReviewRequestDto;
use App\Modules\Task\Enums\ReviewType;
use App\Modules\Task\Http\Requests\StoreReviewRequest;
use App\Modules\Task\Models\UserTask;
use App\Modules\Task\Services\TaskReviewService;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class TaskReviewController extends Controller
{
    public function __construct(
        private readonly TaskReviewService $taskReviewService
    )
    {
        $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     */
    public function getTasksSentByStudents(Request $request)
    {
        $lecture_id = $request->get('lecture_id', 0);
        $task_id = $request->get('task_id', 0);

        Paginator::currentPathResolver(fn() => '/tasks/reports');
        return $this->taskReviewService->getTasksSentByStudents($lecture_id, $task_id)->paginate();
    }


    public function getReview(UserTask $task, User $student) //Collection
    {
        return $this->taskReviewService->latestReview($task->id, $student->id) ?: [ 'review_status' => 'waiting_execution'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        $params = $request->validated();

        if (auth()->user()->isAdmin()) {
            $params['reviewer_id'] = auth()->id();

        } else {
            $params['student_id'] = auth()->id();
            $params['review_status'] = ReviewType::WaitingForReview->value;
        }

        $dto = TaskReviewRequestDto::from($params);

        return $this->taskReviewService->save($dto) ? $dto->toArray() : [];
    }
}
