<?php

namespace App\Modules\Authentication\Repositories\Token;

use App\Modules\Authentication\Dtos\UpdateRefreshTokenDto;
use App\Modules\Authentication\Repositories\Token\Exception\InvalidTokenException;
use App\Modules\Authentication\Repositories\Token\Exception\TokenExpiredException;
use App\Modules\Authentication\Repositories\Token\Exception\TokenRevokedException;

interface TokenRepositoryInterface
{
    public function parseToken(string $access_token, string $secret): Token;

    public function getSessions(string $access_token, string $secret);

    public function generateTokens(TokenPayload $payload, string $secret): TokenPair;

    /**
     * @throws TokenExpiredException
     * @throws InvalidTokenException
     * @throws TokenRevokedException
     */
    public function refreshTokens(UpdateRefreshTokenDto $dto, string $secret): TokenPair;

    public function revokeRefresh(string $value);

    public function revokeAllRefreshTokens(int $uid);

    public function clearSession(string $access_token): bool;
}
