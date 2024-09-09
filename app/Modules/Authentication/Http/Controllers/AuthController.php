<?php

namespace App\Modules\Authentication\Http\Controllers;

use App\Common\AuthStatusCode;
use App\Common\Enums\UserRole;
use App\Common\UserRole as CommonUserRole;
use App\Common\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Account\Repositories\UserActionsLoggingRepository;
use App\Modules\Account\Services\UserActionsLoggingService;
use App\Modules\Authentication\Dtos\CreateAccountDto;
use App\Modules\Authentication\Http\Requests\CreateAccountFormRequest;
use App\Modules\Authentication\Repositories\AuthRepository;
use App\Modules\Authentication\Services\AuthenticatedUser;
use App\Modules\File\Services\FileUploadService;
use DateTimeInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Manager;
use Tymon\JWTAuth\Token;

class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(
        private readonly Manager $manager,
        private readonly JWT $jwt,
        private readonly UserActionsLoggingRepository $loggingRepository,
        private readonly UserActionsLoggingService $loggingService
    )
    {
        $this->middleware('auth:api', ['except' => ['login', 'logout', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws TokenBlacklistedException
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['phone', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Логин или пароль неверен!', 'code' => AuthStatusCode::LoginOrPasswordIncorrect], 401);
        }

        if(!auth()->user()->isActivated()) {
            // Todo: maybe status code should be 403 or another, this fix due to axios interceptor, that reload page when 403 code returned
            // so user doesn't see actual message
            return response()->json(['message' => 'Пользователь не активирован!', 'code' => AuthStatusCode::UserNotActivated], 401);
        }

        // log user auth
        $action = $this->loggingService->userActionLog($request, auth()->id());
        $this->loggingRepository->save($action);

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }


    /**
     * @throws Exception
     */
    public function register(CreateAccountFormRequest $request, FileUploadService $fileUploadService)
    {
        $params = $request->validated();
        $avatar = $request->file('avatar');

        $dto = CreateAccountDto::from($params)
            ->setRole(UserRole::Student)
            ->setStatus(UserStatus::Activated);

        $dto->setPassword(Hash::make($dto->getPassword()));

        if($user = User::create($dto->toArray())) {
            $this->loggingService->log($request, $user->id);
            $auth_user = new AuthenticatedUser($user->id, CommonUserRole::Student);
            $image = $fileUploadService->upload($avatar, $auth_user, 'avatars', fileable_id: $user->id);
            $user->update([
                'avatar' => $image->generated_name
            ]);
            return $user;
        }

        throw new Exception('Cant create user');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        //$this->loggingService->log($request, auth()->id(), UserActionType::Logout);

        auth()?->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws TokenBlacklistedException
     */
    public function refresh(Request $request): JsonResponse
    {
        $accessToken = $request->bearerToken();
        /** @var JWT $auth */
        $auth = auth();

        if ($accessToken) {
            $token = new Token($accessToken);
            return $this->respondWithToken($auth->refresh($token));
        }


        return response()->json(['message' => 'Access token not defined'], 422);
    }


    /*public function refresh(Request $request, AuthRepository $authRepository): JsonResponse
    {
        $clientRefreshToken = $request->bearerToken();
        $refreshToken = $authRepository->getRefreshTokenByValue($clientRefreshToken);

        if($refreshToken) {
            $expired_at = Carbon::createFromTimestamp($refreshToken->expired_at);
            $now = Carbon::now();

            if($expired_at->lt($now)) {
                return response()->json(['message' => 'Invalid refresh token', 'code' => 0x101], 401);
            }
            else {
                $refreshTokenNew = $authRepository->createRefreshToken(self::REFRESH_TOKEN_TTL, auth()?->id(), $request->userAgent(), $request->ip());
                return $this->respondWithToken2($refreshTokenNew, $request, $authRepository);
            }
        }

        return response()->json(['message' => 'Access token not defined'], 422);
    }*/


    public function createRefreshToken($token): string
    {
        /** @var JWTFactory $payload */
        $payload = $this->jwt->factory()->setTTL(env('JWT_REFRESH_TOKEN_TTL'))->setRefreshFlow();

        return $this->manager->encode($payload->make())->get();
    }



    /*public function createRefreshToken2($token): string
    {
        /** @var JWTFactory $payload
        $payload = $this->jwt->factory()->setTTL(self::REFRESH_TOKEN_TTL)->setRefreshFlow();

        return $this->manager->encode($payload->make())->get();
    }*/


    /**
     * @throws TokenBlacklistedException
     */
    public function formatDecodedToken($token): array
    {
        $data = $this->manager->decode(new Token($token))->toArray();
        return array_map(function($item) {
            if(is_int($item) && $item > 1000) return date(DateTimeInterface::RSS, $item);
            return $item;
        }, $data);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     * @throws TokenBlacklistedException
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        $refreshToken = $this->createRefreshToken($token);

        $data = [
            'access_token' => [
                'value' => $token,
                //'type' => 'bearer',
                'created_at' => date(DateTimeInterface::W3C),
                'expires_in' => env('JWT_ACCESS_TOKEN_TTL'),
            ],
            'refresh_token' => [
                'value' => $refreshToken,
                //'type' => 'bearer',
                'created_at' => date(DateTimeInterface::W3C),
                'expires_in' => env('JWT_REFRESH_TOKEN_TTL'),
            ],
            /*'decoded' => [
                'access_token' => $this->formatDecodedToken($token),
                'refresh_token' => $this->formatDecodedToken($refreshToken)
            ],*/
            'user' => auth()->user()
        ];

        // dd($data);

        return response()->json($data);
    }



    protected function respondWithTokenNew(string $token, Request $request, AuthRepository $authRepository): JsonResponse
    {
        $refreshToken = $authRepository->createRefreshToken(env('JWT_REFRESH_TOKEN_TTL'), auth()?->id(), $request->userAgent(), $request->ip());

        return response()->json([
            'access_token' => [
                'value' => $token,
                //'type' => 'bearer',
                'created_at' => date(DateTimeInterface::W3C),
                'expires_in' => env('JWT_ACCESS_TOKEN_TTL'),
            ],
            'refresh_token' => [
                'value' => $refreshToken->token,
                //'type' => 'bearer',
                'created_at' => date(DateTimeInterface::W3C),
                'expires_in' => env('JWT_REFRESH_TOKEN_TTL'),
            ],
            /*'decoded' => [
                'access_token' => $this->formatDecodedToken($token),
                'refresh_token' => $this->formatDecodedToken($refreshToken)
            ],*/
            'user' => auth()->user()
        ]);
    }
}
