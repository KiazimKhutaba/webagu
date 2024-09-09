<?php

namespace App\Modules\Authentication\Repositories\Token;

class Token
{
    public function __construct(
        public readonly string $issuer,
        public readonly int $issued_at,
        public readonly int $expires_at,
        public readonly int $user_id,
        public readonly string $user_role,
        public readonly int $user_role_id,
        public readonly bool $is_vip = false,
        public readonly ?int $office_id = null,
    )
    {}

    public static function from(array $data): Token
    {
        return new self(
            $data['iss'],
            $data['iat'],
            $data['exp'],
            $data['user_id'],
            $data['user_role'],
            $data['user_role_id'],
            $data['is_vip'],
            $data['office_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return  [
            'iss' => $this->issuer,
            'iat' => now()->timestamp,
            'exp' => $this->expires_at,
            'user_id' => $this->user_id,
            'user_role' => $this->user_role,
            'user_role_id' => $this->user_role_id,
            'is_vip' => $this->is_vip,
            'office_id' => $this->office_id
        ];
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
