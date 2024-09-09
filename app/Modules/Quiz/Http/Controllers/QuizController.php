<?php

namespace App\Modules\Quiz\Http\Controllers;

use App\Common\Exceptions\AccessToQuizForbiddenException;
use App\Common\Exceptions\ActionNotAuthorizedException;
use App\Http\Controllers\Controller;
use App\Modules\Authentication\Services\AuthenticatedUser;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\File\Enums\FileDestinations;
use App\Modules\File\Models\File;
use App\Modules\File\Services\FileUploadService;
use App\Modules\Quiz\Dtos\GrantUserAccessToQuizDto;
use App\Modules\Quiz\Dtos\QuizDto;
use App\Modules\Quiz\Enums\PassedQuizStatus;
use App\Modules\Quiz\Http\Requests\GrantUserAccessToQuizRequest;
use App\Modules\Quiz\Http\Requests\Quiz\StoreQuizRequest;
use App\Modules\Quiz\Http\Requests\Quiz\UpdateQuizRequest;
use App\Modules\Quiz\Http\Requests\QuizReplyFileRequest;
use App\Modules\Quiz\Models\PassedQuiz;
use App\Modules\Quiz\Models\Quiz;
use App\Modules\Quiz\Services\QuizService;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;


class QuizController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService,
        private readonly QuizService           $quizService
    )
    {
    }


    public function getQuizAccessRequests(string $uuid)
    {
        return $this->quizService->getQuizAccessRequests($uuid)->get();
    }

    public function grantUserAccessToQuiz(GrantUserAccessToQuizRequest $request)
    {
        return $this->quizService->grantAccessToQuiz(GrantUserAccessToQuizDto::from($request->validated()));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());
        $builder = Quiz::query();

        if ($user->isStudent()) {
            $builder->with(['accessRequest' => static function (HasOne $query) use ($user) {
                return $query->where(['user_id' => $user->id]);
            }]);
        }

        return $builder->orderBy('created_at', 'desc')->paginate();
    }


    public function addReplyFile(QuizReplyFileRequest $request, FileUploadService $uploadService): array
    {
        $fileable_id = $request->validated('fileable_id');
        $fileable_type = $request->validated('fileable_type');
        $file = $request->file('file');
        $user = $this->authenticationService->getAuthenticatedUserX(auth());

        $res = $uploadService->uploadFile($file, $user, FileDestinations::Uploads, $fileable_type, $fileable_id);

        return $res->toArray();
    }


    public function removeReplyFile(int $id)
    {
        $authenticatedUser = $this->authenticationService->getAuthenticatedUserX(auth());
        $file = File::find($id);

        if ($file) {
            if ($authenticatedUser->isAdmin()) {
                return ['status' => $file->delete()];
            }

            if ($authenticatedUser->isStudent()) {
                if ($authenticatedUser->id !== $file->user_id) {
                    return new ActionNotAuthorizedException('File does\'t belong to user');
                }

                return ['status' => $file->delete()];
            }
        }

        return $file;
    }


    public function sendOnReview(Request $request)
    {
        // $authenticated_user = $this->authenticationService->getAuthenticatedUserX(auth());

        $id = $request->input('id');
        $model = PassedQuiz::find($id);

        $res = $model->update([
           'status' => PassedQuizStatus::OnReview
        ]);

        return compact('res');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuizRequest $request)
    {
        $params = $request->validated();
        $params['themes'] = json_decode($params['themes']);
        $params['theme_names'] = json_decode($params['theme_names']);
        $params['assigned_to'] = json_decode($params['assigned_to']);

        // Todo: on create return id value instead of uuid
        $new = Quiz::create($params)->toArray();
        return [...$new, 'uuid' => $params['uuid']];
    }

    /**
     * Display the specified resource.
     * @throws AccessToQuizForbiddenException|Exception
     */
    public function beginQuiz(Quiz $quiz)
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());

        if ($quiz->can_regenerate) {
            return $this->getRandomQuiz($quiz);
        }

        if ($user->isStudent() && !$this->hasAccess($quiz, $user)) {
            return [
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'access_granted' => false
                ]
            ];
        }


        if ($quiz->is_midterm) {
            return $this->getMidtermQuiz($quiz);
        }

        return $this->getSeminarQuiz($quiz);
    }

    /**
     * Generates random quiz questions, tasks etc.
     *
     * @param Quiz $quiz
     * @return array
     * @throws Exception
     */
    public function getRandomQuiz(Quiz $quiz): array
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());
        $randomQuiz = $quiz->generateRandomQuestions($user->id);

        return [
            'quiz' => [
                ...$quiz->toArray(),
                'access_granted' => true
            ],
            'items' => [
                'questions' => $randomQuiz['questions'],
                'tasks' => $randomQuiz['tasks'],
                'quizzes' => $randomQuiz['quizzes'],
            ]
        ];
    }

    protected function hasAccess(Quiz $quiz, AuthenticatedUser $user): bool
    {
        // this is exam or midterm quiz
        $accessNotGranted = !$this->quizService->userGrantedAccessToQuiz($quiz->id, $user->id);

        if ($accessNotGranted) {
            $this->quizService->sendRequestToAccessQuiz($user->id, $quiz->id);
            return false;
        }

        return true;
    }

    /**
     * Generates quiz for midterm exam
     *
     * @param Quiz $quiz
     * @return array
     */
    public function getMidtermQuiz(Quiz $quiz): array
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());
        $is_question = fn($q) => ($q->type === 'question');
        $is_task = fn($q) => ($q->type === 'task');

        $condition = ['quiz_id' => $quiz->id, 'student_id' => $user->id];

        if ($model = PassedQuiz::where($condition)->first()) {
            $condition['pass_count'] = ++$model->pass_count;
            $model->update($condition);

            return [
                'quiz' => [
                    ...$model->getQuiz()->toArray(),
                    'title' => $quiz->title,
                    'access_granted' => true
                ],
                'items' => $model->getPassedMidtermQuiz(),
                'passed_quiz' => [
                    'id' => $model->id,
                    'status' => $model->status
                ]
            ];
        } else {

            $midtermQuiz = collect($quiz->generateMidtermQuiz($quiz->themes, $quiz->number_of_questions, $quiz->number_of_tasks));

            $questions = $midtermQuiz->filter($is_question)->values();
            $tasks = $midtermQuiz->filter($is_task)->values();

            $passed = [
                'quiz_id' => $quiz->id,
                'quiz_uuid' => $quiz->uuid,
                'student_id' => $user->id,
                'quiz_title' => $quiz->title,
                'questions' => $questions->pluck('id'),
                'tasks' => $tasks->pluck('id'),
                'quizzes' => [],
            ];

            $passed_quiz = PassedQuiz::create($passed);

            return [
                'quiz' => [
                    ...$quiz->toArray(),
                    'access_granted' => true,
                ],
                'items' => [
                    'questions' => $questions,
                    'tasks' => $tasks,
                    'quizzes' => []
                ],
                'passed_quiz' => [
                    'id' => $passed_quiz->id,
                    'status' => $model->status
                ]
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        $is_available = $request->validated(['status']);
        return $quiz->update(['is_available' => $is_available]);
    }

    /**
     * Generates non-changing quiz for seminar
     *
     * @return array
     * @throws Exception
     */
    public function getSeminarQuiz(Quiz $quiz)
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());
        $randomQuiz = $quiz->generateRandomQuestions($user->id);

        $condition = ['quiz_id' => $quiz->id, 'student_id' => $user->id];
        if ($model = PassedQuiz::where($condition)->first()) {
            $condition['pass_count'] = ++$model->pass_count;
            $model->update($condition);

            return (new QuizDto())
                ->setQuiz($model->getQuiz()->toArray())
                ->setQuizTitle($quiz->title)
                ->setItems($model->getPassedQuiz())
                ->setPassedQuizId($model->id)
                ->setPassedQuizStatus(PassedQuizStatus::from($model->status))
                ->setReplyFiles($model->replyFiles->toArray())
                ->toArray();
        }

        $passed_quiz = PassedQuiz::create([
            'quiz_id' => $quiz->id,
            'quiz_uuid' => $quiz->uuid,
            'student_id' => $user->id,
            'quiz_title' => $quiz->title,
            'questions' => $randomQuiz['questions_ids'],
            'tasks' => $randomQuiz['tasks_ids'],
            'quizzes' => $randomQuiz['quizzes_ids'],
        ]);

        return (new QuizDto())
            ->setQuiz($quiz->toArray())
            ->setQuestions($randomQuiz['questions'])
            ->setTasks($randomQuiz['tasks'])
            ->setQuizzes($randomQuiz['quizzes'])
            ->setPassedQuizId($passed_quiz->id)
            ->setPassedQuizStatus(PassedQuizStatus::Waiting)
            ->toArray();
    }

    public function getPassedQuiz(PassedQuiz $quiz): array
    {
        // Todo: add access control

        $questions = $quiz->getQiuzQuestions();
        return ['quiz' => $quiz->getPassedQuizBy($quiz->id), 'items' => $questions];
    }

    public function getPassedQuizzes(PassedQuiz $quiz)
    {
        return $quiz->getPassedQuizzes();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        return $quiz->delete();
    }
}
