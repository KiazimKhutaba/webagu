<?php

namespace App\Modules\Quiz\Services;

use App\Common\Enums\AccesibleType;
use App\Common\Models\AccessRequest;
use App\Modules\Quiz\Dtos\GrantUserAccessToQuizDto;
use App\Modules\Quiz\Dtos\UserAccessRequestDto;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function __construct()
    {
    }

    public function getUserQuizAccessRequest(int $quiz_id, int $user_id): ?UserAccessRequestDto
    {
        $query = $this->getQuizAccessRequests($quiz_id)->where(['user_id' => $user_id]);
        $data = $query->first()->toArray();
        return count($data) > 0 ? UserAccessRequestDto::from($data) : null;
    }

    /**
     * Show users that request access to quiz
     */
    public function getQuizAccessRequests(string $uuid)
    {
        return AccessRequest::from('access_requests as ar')
            ->select([
                'ar.id as request_id',
                'ar.user_id',
                DB::raw("CONCAT(u.lastname, ' ', u.firstname) as full_name"),
                'ar.granted',
                'qz.title as quiz_title',
                'qz.uuid as quiz_uuid',
                'ar.created_at'
            ])
            ->join('quizzes as qz', function ($join) use ($uuid) {
                $join->on('qz.id', '=', 'ar.accessible_id')->where('qz.uuid', '=', $uuid);
            })
            ->join('users as u', 'u.id', '=', 'ar.user_id')
            ->where('ar.accessible_type', '=', AccesibleType::Quiz);
    }

    public function userGrantedAccessToQuiz(int $quiz_id, int $user_id): bool
    {
        return AccessRequest::where([
            'user_id' => $user_id,
            'granted' => true,
            'accessible_id' => $quiz_id,
            'accessible_type' => AccesibleType::Quiz,
        ])->exists();
    }

    /**
     * Save user access request
     *
     * @param int $user_id
     * @param int $quiz_id
     * @return int
     */
    public function sendRequestToAccessQuiz(int $user_id, int $quiz_id): int
    {
        $data = [
            'user_id' => $user_id,
            'accessible_id' => $quiz_id,
            'accessible_type' => AccesibleType::Quiz,
            //'granted' => false,
        ];

        return AccessRequest::upsert(
            values: $data,
            uniqueBy: ['user_id', 'accessible_id'],
            //update: ['granted']
        );

    }


    /**
     * Grant access to resource for one or more users
     *
     * @param int|array<int> $users
     * @param int $quiz_id
     * @param bool $granted
     * @return int
     */
    public function grantAccessToQuiz(GrantUserAccessToQuizDto $dto)
    {
        return AccessRequest::find($dto->request_id)->update([
            'granted' => true
        ]);
    }
}
