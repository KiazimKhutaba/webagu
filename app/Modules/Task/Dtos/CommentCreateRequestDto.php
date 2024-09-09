<?php

namespace App\Modules\Task\Dtos;

use App\Common\Dtos\RequestDtoInterface;
use Illuminate\Http\UploadedFile;

class CommentCreateRequestDto implements RequestDtoInterface
{

    public function __construct
    (
        public readonly int $senderId,
        public readonly int $receiverId,
        public readonly string $commentableType,
        public readonly int $commentableId,
        public readonly string $message,
        /** @type UploadedFile[] */
        public readonly ?array $files = null
    )
    {}

    public static function from(array $input): self
    {
        return new self(
            $input['sender_id'],
            $input['receiver_id'],
            $input['commentable_type'],
            $input['commentable_id'],
            $input['message'],
            $input['files'] ?? null
            //json_decode($input['files'])
        );
    }

    public function rules(): array
    {
        return [];
    }

    public function toArray(): array
    {
        return [
            'commentable_type' => $this->commentableType,
            'commentable_id' => $this->commentableId,
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'message' => $this->message,
            //'ids' => $this->files // files ids
        ];
    }
}
