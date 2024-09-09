<?php

namespace App\Modules\Authentication\Repositories;

use App\Models\User;
use App\Modules\Authentication\Dtos\UserRegistrationDto;
use App\Modules\Authentication\Repositories\Token\TokenPayload;
use App\Modules\Authentication\Repositories\Token\TokenRepositoryInterface;
use App\Modules\Authentication\Repositories\Token\TokenPair;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Throwable;


class AuthenticationRepository implements AuthenticationRepositoryInterface
{

    public function __construct(
        private readonly TokenRepositoryInterface $tokenRepository,
        //private readonly UserSettingsRepository   $userSettingsRepository
    )
    {
    }

    /**
     * @throws Exception|Throwable
     */
    public function createUser(UserRegistrationDto $dto, string $jwt_secret): TokenPair
    {
        try {
            DB::beginTransaction();

            /*$verifyModel = PhoneVerification::where([
                'phone' => $dto->getPhone(),
                'code' => $dto->getCode()
            ]);*/

            /*if (!$verifyModel->exists())
                throw new Exception('Неправильный номер или код'); // Todo: change to specific exception*/

            $dto->setPhoneVerifiedAt(now());

            // write telegram chat id
            /*$bot_state = BotState::where(['phone' => $dto->getPhone()])->first();
            $dto->setTelegramChatId($bot_state->chat_id);*/

            /** @var User $user */
            $user = User::create($dto->toArray());

            // if user created by himself, enable tg notifications by default
            /*if (is_null($user->creator_id)) {
                $this->userSettingsRepository->setTelegramNotifications(true, $user->id);
            }*/

            $tokens = $this->tokenRepository->generateTokens(
                TokenPayload::getDefault(
                    $user->id, $user->role, $user->full_name,
                ),
                $jwt_secret
            );

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return $tokens;
    }

    public function getAll()
    {
        return User::all()->toArray();
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidPasswordException
     * @throws AccountNotActivatedException
     */
    public function authenticate(array $credentials, string $jwt_secret): TokenPair
    {
        /* @var Builder $model */
        $model = User::where([
            'phone' => $credentials['phone'],
        ]);

        if ($model->exists()) {
            /** @var User $user */
            $user = $model->first();

            if(!$user->isActivated()) {
                throw new AccountNotActivatedException("Ошибка доступа. Обратитесь к менеджеру");
            }

            if ($user->passwordEquals($credentials['password'])) {

                $payload = TokenPayload::getDefault(
                    $user->id,
                    $user->role,
                    $user->full_name,
                );
                return $this->tokenRepository->generateTokens($payload, $jwt_secret);
            } else {
                throw new InvalidPasswordException();
            }
        } else {
            throw new UserNotFoundException();
        }
    }

    public function createPhoneVerificationCode(string $phone, string $ip, string $user_agent, string $sender_name): int
    {
        $verificationCode = mt_rand(100000, 999999);

        $model = PhoneVerification::where(['phone' => $phone]);

        if ($model->exists()) {
            $obj = $model->first();
            $obj->update([
                'ip' => $ip,
                'user_agent' => $user_agent,
                'code' => $verificationCode,
                'attempts_count' => $obj->attempts_count + 1,
            ]);
        } else {
            PhoneVerification::create([
                'phone' => $phone,
                'ip' => $ip,
                'user_agent' => $user_agent,
                'code' => $verificationCode,
                'attempts_count' => 1,
                'service' => $sender_name
            ]);
        }

        return $verificationCode;
    }


    public function getProfileData(int $user_id)
    {
        return User::with('city')->with('office')->find($user_id);
    }

}
