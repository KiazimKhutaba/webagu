<?php

namespace App\Modules\Quiz\Dtos;

class GrantUserAccessToQuizDto
{

    public function __construct(
        public readonly int $user_id,
        public readonly int $request_id
    )
    {
    }


    public static function from(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            request_id: $data['request_id']
        );
    }


    public function toArray(): array
    {
        return [
          'id' => $this->request_id,
          'user_id' => $this->user_id,
        ];
    }
}
