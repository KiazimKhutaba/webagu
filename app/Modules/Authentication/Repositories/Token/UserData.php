<?php

namespace App\Modules\Authentication\Repositories\Token;

class UserData
{

    public function __construct(
        public readonly int $user_id,
        public readonly ?string $role = null,
    )
    {}
}
