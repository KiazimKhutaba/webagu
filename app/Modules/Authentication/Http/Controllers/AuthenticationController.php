<?php

namespace App\Modules\Authentication\Http\Controllers;

use App\Common\UserRole;
use App\Common\UserStatus;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Dtos\UpdateRefreshTokenDto;
use App\Modules\Authentication\Dtos\UserRegistrationDto;
use App\Modules\Authentication\Http\Requests\LoginRequest;
use App\Modules\Authentication\Http\Requests\UserRegistrationRequest;
use App\Modules\Authentication\Http\Responses\InvalidCredentialsResponse;
use App\Modules\Authentication\Http\Responses\UnauthenticatedResponse;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\Services\NotAuthenticatedException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;
use Throwable;


class AuthenticationController extends Controller
{

    public function __construct(
        private readonly AuthenticationService $authenticationService
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function register(UserRegistrationRequest $request)
    {
        $userRegistrationDto = UserRegistrationDto::from(array_merge($request->validated(), [
            'role' => UserRole::Student->value,
            'status' => UserStatus::Activated->value
        ]));

        $tokenPair = $this->authenticationService->register($userRegistrationDto);
        return response()->json($tokenPair);
    }


    public function login(LoginRequest $request, InvalidCredentialsResponse $response)
    {
        $credentials = $request->validated();

        try {
            return response()->json($this->authenticationService->login($credentials));

        } catch (NotAuthenticatedException) {
            return response()->json($response->toArray(), $response->httpCode);
        }
    }

    public function logout(Request $request)
    {
        $access_token = $request->bearerToken();

        if ($access_token) {
            $this->authenticationService->logout($access_token);
        }
    }

    public function refresh(Request $request, UnauthenticatedResponse $response)
    {
        $dto = (new UpdateRefreshTokenDto())
            ->setRefreshTokenValue($request->bearerToken())
            ->setRefreshTtl(env('JWT_REFRESH_TOKEN_TTL'))
            ->setUserAgent($request->userAgent());

        try {
            return response()->json($this->authenticationService->refresh($dto));
        } catch (Exception $e) {
            Log::log(LogLevel::DEBUG, $e);
        }

        return response()->json($response->toArray(), $response->httpCode);
    }

    /**
     * Returns currently logged in customer
     *
     * @return Collection|Builder|Builder[]|Model|null
     */
    public function getAuthenticated(Request $request)
    {
        return $this->authenticationService->getProfileData($request->bearerToken());
    }


    public function getUser(Request $request)
    {
        return $this->authenticationService->getAuthenticatedUser($request->bearerToken());
    }

    public function userSessions(Request $request)
    {
        return $this->authenticationService->getSessions($request->bearerToken());
    }
}
