<?php

namespace App\Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\File\Models\File;
use App\Modules\Task\Enums\CommentableType;
use App\Modules\Task\Http\Requests\CreateUserTaskRequest;
use App\Modules\Task\Http\Requests\UserTasksColumnsRequest;
use App\Modules\Task\Http\Requests\UserTaskUpdateRequest;
use App\Modules\Task\Models\Comment;
use App\Modules\Task\Models\UserTask;
use App\Modules\Task\Repositories\TasksRepository;
use App\Modules\Task\Services\TaskService;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Deprecated;

class UserTasksController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly AuthenticationService $authenticationService,
    )
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UserTasksColumnsRequest $request)
    {
        $fields = $request->validated('fields', ['*']);
        $lecture_id = intval($request->get('lecture_id', 0));

        return $this->taskService->getStudentTasks(auth()->id(), $lecture_id, $fields);
    }


    public function getProgressByTasksForUser(int $id)
    {
        return TasksRepository::getProgressByTasksForUser($id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserTaskRequest $request)
    {
        $params = $request->only(['title', 'description', 'is_visible', 'lecture_id']);
        $ids = json_decode($request->get('files'));

        $params['user_id'] = auth()->id();
        $obj = UserTask::create($params);

        File::whereIn('id', $ids)->update([
            'fileable_type' => CommentableType::Task,
            'fileable_id' => $obj->id
        ]);

        return $obj;
    }

    /**
     * Display the specified resource.
     */
    #[Deprecated]
    public function show(int $id)
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());
        return $this->taskService->getStudentTask($id, auth()->id(), $user);

        $files = File::where(['fileable_type' => CommentableType::Task, 'fileable_id' => $task->id])->get();
        $data = [...$task->withReview(auth()->id())->toArray(), 'files' => $files];

        /** @var User $user */
        $user = auth()->user();

        if ($user->role !== 'admin') {
            $comments = $task->comments(auth()->id())->with('files')->with('user')->get();
            $data['comments'] = $comments;
        }

        if ($user->role === 'admin') {
            // Todo: refactor this maybe :)
            // $request->input('all_students', false)
            $data['students'] = $task->usersThatSentReply();
        }

        return $data;
    }


    public function comments(Request $request, UserTask $task)
    {
        $params = $request->only(['student_id']);

        if(!$params) {
            return [];
        }

        return $this->taskService->getStudentTaskHistory($task->id, intval($params['student_id']));
    }


    public function toggleVisibility(Request $request, UserTask $task)
    {
        $isOk = $task->update([
            'id' => $task->id,
            'is_visible' => $request->get('is_visible', 'false')
        ]);

        return ['isOk' => $isOk];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserTaskUpdateRequest $request, UserTask $task)
    {
        $validated = $request->validated();

        $isOk = $task->update([
            'id' => $task->id,
            ...$validated
        ]);

        return ['isOk' => $isOk];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserTask $userTask)
    {
        Comment::where(['commentable_id' => $userTask->id, 'commentable_type' => CommentableType::Task])->delete();
        return ['result' => $userTask->delete(), 'id' => $userTask->id];
    }
}
