<?php

namespace App\Modules\Task\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Task\Dtos\CommentCreateRequestDto;
use App\Modules\Task\Enums\CommentableType;
use App\Modules\Task\Http\Requests\CommentStoreRequest;
use App\Modules\Task\Models\Comment;
use App\Modules\Task\Models\TaskHistory;
use App\Modules\Task\Services\CommentsService;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentsService $commentsService
    )
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request)
    {
        $params = $request->validated();
        $params['sender_id'] = auth()->id();
        $params['commentable_type'] = CommentableType::TaskHistory->value;
        $dto = CommentCreateRequestDto::from($params);

        return $this->commentsService->addComment($dto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskHistory $taskHistoryItem)
    {
        // Todo: add files removing
        return $taskHistoryItem->delete();
    }
}
