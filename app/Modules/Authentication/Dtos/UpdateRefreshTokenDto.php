<?php

namespace App\Modules\Authentication\Dtos;

class UpdateRefreshTokenDto
{
    private string $refresh_token_value;
    private int $refresh_ttl;
    private int $user_id;
    private string $user_agent;


    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): UpdateRefreshTokenDto
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getRefreshTtl(): int
    {
        return $this->refresh_ttl;
    }

    public function setRefreshTtl(int $refresh_ttl): UpdateRefreshTokenDto
    {
        $this->refresh_ttl = $refresh_ttl;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->user_agent;
    }

    public function setUserAgent(?string $user_agent): UpdateRefreshTokenDto
    {
        $this->user_agent = $user_agent;
        return $this;
    }

    public function getRefreshTokenValue(): string
    {
        return $this->refresh_token_value;
    }

    public function setRefreshTokenValue(string $refresh_token_value): UpdateRefreshTokenDto
    {
        $this->refresh_token_value = $refresh_token_value;
        return $this;
    }

}
