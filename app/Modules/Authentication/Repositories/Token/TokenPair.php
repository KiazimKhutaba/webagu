<?php

namespace App\Modules\Authentication\Repositories\Token;

class TokenPair
{

    public function __construct(
        public readonly string $access_token,
        public readonly string $refresh_token,
    )
    {}


    public function toArray(): array {
        return [
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

}
