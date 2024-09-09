<?php

namespace App\Modules\Authentication\Http\Responses;

class UnauthenticatedResponse
{
    public function __construct(
        public readonly string $message = 'Unauthenticated',
        public readonly int $appCode = 401,
        public readonly int $httpCode = 401
    )
    {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'code' => $this->appCode,
        ];
    }
}
