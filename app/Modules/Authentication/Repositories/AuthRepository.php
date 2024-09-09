<?php

namespace App\Modules\Authentication\Repositories;

use App\Modules\Authentication\Models\RefreshToken;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthRepository
{
    public function createRefreshToken(int $ttl, int $user_id, string $user_agent, string $client_ip)
    {
        $datetime = Carbon::now();

        $data = [
            'token' => Str::random(64),
            'expired_at' => $datetime->addMinutes($ttl),
            'user_id' => $user_id,
            'user_agent' => $user_agent,
            'client_ip' => $client_ip
        ];

        return RefreshToken::create($data);
    }


    public function getRefreshTokenByValue(string $value): ?RefreshToken
    {
        return RefreshToken::where(['token' => $value])->first();
    }
}
