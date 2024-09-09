<?php

namespace App\Modules\Authentication\Services;

use App\Common\UserRole;
use App\Models\User;
use App\Modules\Authentication\Dtos\UpdateRefreshTokenDto;
use App\Modules\Authentication\Dtos\UserRegistrationDto;
use App\Modules\Authentication\Repositories\AccountNotActivatedException;
use App\Modules\Authentication\Repositories\AuthenticationRepository;
use App\Modules\Authentication\Repositories\Token\Exception\InvalidTokenException;
use App\Modules\Authentication\Repositories\Token\Exception\TokenExpiredException;
use App\Modules\Authentication\Repositories\Token\Exception\TokenRevokedException;
use App\Modules\Authentication\Repositories\Token\TokenPair;
use App\Modules\Authentication\Repositories\Token\TokenPayload;
use App\Modules\Authentication\Repositories\Token\TokenRepositoryInterface;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\Response;

class AuthenticationService
{

    public function __construct(
        private readonly AuthenticationRepository $authenticationRepository,
        private readonly TokenRepositoryInterface $tokenRepository,
        private readonly string $jwtSecret,
        private readonly int $refreshTTL,
    )
    {
    }


    /**
     * Creates user account and returns access_token/refresh_token pair
     *
     * @param UserRegistrationDto $dto
     * @return TokenPair
     * @throws \Throwable
     */
    public function register(UserRegistrationDto $dto): TokenPair
    {
        return $this->authenticationRepository->createUser($dto, $this->jwtSecret);
    }

    /**
     * @throws NotAuthenticatedException
     * @throws AccountNotActivatedException
     */
    public function login(array $credentials): TokenPair
    {
        try {
            return $this->authenticationRepository->authenticate($credentials, $this->jwtSecret);
        } catch (AccountNotActivatedException $e1) {
            throw $e1;
        } catch (Exception $e) {
            throw new NotAuthenticatedException($e->getMessage());
        }
    }


    public function logout(string $access_token): bool
    {
        return $this->tokenRepository->clearSession($access_token);
    }

    public function getUser(string $token): AuthenticatedUser
    {
        $token = $this->tokenRepository->parseToken($token, $this->jwtSecret);
        return new AuthenticatedUser($token->user_id, UserRole::from($token->user_role_id));
    }


    public function getProfileData(string $token)
    {
        $user = $this->getUser($token);
        return $this->authenticationRepository->getProfileData($user->id);
    }


    /**
     * Returns access_token/refresh_token pair
     *
     * @param int $uid
     * @param string $user_role
     * @return TokenPair
     */
    public function getTokens(int $uid, string $user_role, string $full_name): TokenPair
    {
        return $this->tokenRepository->generateTokens(
            TokenPayload::getDefault($uid, $user_role, $full_name), $this->jwtSecret
        );
    }


    /**
     * @param UpdateRefreshTokenDto $dto
     * @return TokenPair
     * @throws InvalidTokenException
     * @throws TokenExpiredException
     * @throws TokenRevokedException
     */
    public function refresh(UpdateRefreshTokenDto $dto): TokenPair
    {
        return $this->tokenRepository->refreshTokens($dto, $this->jwtSecret);
    }


    public function getSessions(string $access_token)
    {
        return $this->tokenRepository->getSessions($access_token, $this->jwtSecret);
    }


    public function getAuthenticatedUser(?string $access_token): ?array
    {
        if (null !== $access_token) {
            $token = $this->tokenRepository->parseToken($access_token, $this->jwtSecret);
            return [
                'user_id' => $token->user_id,
                'user_role' => UserRole::from($token->user_role)
            ];
        }

        return null;
    }


//    public function sendPhoneVerificationCode(
//        string $phone, string $ip, string $user_agent, NotificationSender $notificationSender): Response
//    {
//        $verificationCode = $this->authenticationRepository->createPhoneVerificationCode(
//            $phone, $ip, $user_agent, $notificationSender->getServiceName()
//        );
//
//        return $notificationSender->send("Код восстановления доступа: $verificationCode", $phone);
//    }


//    public function generateVerificationCommand(
//        PhoneVerificationsRepository $verifications, string $ip, string $ua, string $phone): CommandArguments
//    {
//        $code = $verifications->generateCode();
//        $token = $verifications->generateToken();
//
//        if($verifications->contains($phone)) {
//            $verifications->
//        }
//        else {
//
//        }
//
//        $data = [
//            'phone' => $phone,
//            'code' => $code,
//            'token' => $token,
//            'ip' => $ip,
//            'user_agent' => $ua,
//            'service' => 'telegram'
//        ];
//
//        $verifications->saveToken(PhoneVerificationDto::from($data));
//
//        return CommandArguments::make('verify', $token);
//    }

    public function buildAuthenticatedUser(int $id, UserRole $role): AuthenticatedUser
    {
        return new AuthenticatedUser($id, $role);
    }

    /**
     * Returns test user
     *
     * @param AuthManager|Guard $auth
     * @return AuthenticatedUser
     */
    public function getAuthenticatedUserX(Guard|AuthManager $auth): AuthenticatedUser
    {
        /** @var User $user */
        $user = $auth->user();
        return new AuthenticatedUser(auth()->id(), UserRole::fromString($user->role));
    }
}
