<?php

namespace App\Common\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTHelper
{
    public function encode(array $payload, string $key): string
    {
        return JWT::encode($payload, $key, 'HS256');
    }

    public function decode(string $token, string $key): array
    {
        return (array)JWT::decode($token, new Key($key, 'HS256'));
    }
}
