<?php

namespace App\Modules\Authentication\Repositories\Token;

use App\Common\UserRole;

class TokenPayload
{

    public function __construct(
        public readonly string $issuer,
        public readonly int    $issued_at,
        public readonly int    $expires_at,
        public readonly int    $user_id,
        public readonly int    $role,
        public readonly string $user_agent,
        public readonly string $ip,
        public readonly string $full_name,
        public bool            $reports_available = false, // Todo: maybe this is not good place for this,
        public readonly bool   $is_vip = false,
        public readonly ?int   $office_id = null,
    )
    {
    }

    public static function getDefault(
        int    $user_id,
        int    $user_role,
        string $full_name,
        bool   $reports_available = false,
        bool   $is_vip = false,
        ?int   $office_id = null,
    ): TokenPayload
    {
        return new self(
            env('APP_URL'),
            now()->timestamp,
            now()->addSeconds(env('JWT_ACCESS_TOKEN_TTL'))->timestamp,
            $user_id,
            $user_role,
            request()->userAgent(),
            request()->ip(),
            $full_name,
            $reports_available,
            $is_vip,
            $office_id
        );
    }

    public function toArray(): array
    {
        return [
            'iss' => $this->issuer,
            'iat' => now()->timestamp,
            'exp' => $this->expires_at,
            'user_id' => $this->user_id,
            'user_role' => UserRole::from($this->role)->name(),
            'user_role_id' => $this->role,
            'full_name' => $this->full_name,
            'reports_available' => $this->reports_available,
            'is_vip' => $this->is_vip,
            'office_id' => $this->office_id,
        ];
    }
}
