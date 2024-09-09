<?php

namespace App\Modules\Authentication\Http\Responses;

class InvalidCredentialsResponse
{
    public function __construct(
        public readonly string $message = 'Неправильный логин или пароль',
        public readonly int $appCode = 40101,
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
