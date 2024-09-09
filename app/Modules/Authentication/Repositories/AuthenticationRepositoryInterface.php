<?php

namespace App\Modules\Authentication\Repositories;

use App\Modules\Authentication\Dtos\UserRegistrationDto;
use App\Modules\Authentication\Repositories\Token\TokenPair;
use Exception;

interface AuthenticationRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function createUser(UserRegistrationDto $dto, string $jwt_secret): TokenPair;

    public function getAll();

    /**
     * @throws UserNotFoundException
     * @throws InvalidPasswordException
     */
    public function authenticate(array $credentials, string $jwt_secret): TokenPair;
}
