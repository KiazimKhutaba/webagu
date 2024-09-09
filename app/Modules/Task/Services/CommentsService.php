<?php

namespace App\Modules\Task\Services;

use App\Common\Files\FileUploadService;
use App\Modules\Task\Dtos\CommentCreateRequestDto;
use App\Modules\Task\Models\Comment;

class CommentsService
{

    public function __construct(
        private readonly FileUploadService $fileUploadService
    )
    {
    }

    public function addComment(CommentCreateRequestDto $dto): array
    {
        $comment = Comment::create($dto->toArray());

        if(count($dto->files) > 0) {
            foreach ($dto->files as $file) {
                $this->fileUploadService->upload($file, $comment->sender_id, fileable_type: $dto->commentableType, fileable_id: $dto->commentableId);
            }
        }

        return [...$comment->toArray(), 'user' => $comment->user()->first(), 'files' => $comment->files()->get()];
    }
}
