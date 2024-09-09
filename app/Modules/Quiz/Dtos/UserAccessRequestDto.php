<?php

namespace App\Modules\Quiz\Dtos;

use App\Common\Enums\AccesibleType;

class UserAccessRequestDto
{

    public function __construct(
        public readonly int           $user_id,
        public readonly int           $accessible_id,
        public readonly AccesibleType $accessible_type,
        public readonly bool          $granted,
    )
    {
    }


    public static function from(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            accessible_id: $data['accessible_id'],
            accessible_type: AccesibleType::from($data['accessible_type']),
            granted: boolval($data['granted'])
        );
    }
}
