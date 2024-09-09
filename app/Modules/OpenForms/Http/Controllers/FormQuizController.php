<?php

namespace App\Modules\OpenForms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Authentication\Services\AuthenticationService;
use App\Modules\Authentication\Services\AuthService;
use App\Modules\OpenForms\Http\Requests\CreateFormQuizRequest;
use App\Modules\OpenForms\Models\FormQuestion;
use App\Modules\OpenForms\Models\FormQuiz;
use App\Modules\OpenForms\Models\QuestionOption;
use App\Modules\OpenForms\Models\QuizTake;
use App\Modules\OpenForms\Models\QuizTakeOption;
use App\Modules\Quiz\Http\Requests\Quiz\AcceptQuizTakeForReviewRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FormQuizController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService
    )
    {
        $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     */
    public function getListOfFormQuizzes()
    {
        return FormQuiz::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createFormQuiz(CreateFormQuizRequest $request)
    {
        $validated = $request->validated();

        $quiz = $validated['quiz'];
        $questions = $validated['questions'];
        $options = $validated['options'] ?? [];

        $timestamp = Carbon::now();

        foreach ($questions as &$question) {
            $question['created_at'] = $timestamp;
            $question['updated_at'] = $timestamp;
        }


        if(($options_count = count($options)) > 0)
        {
            foreach ($options as &$option) {
                $option['created_at'] = $timestamp;
                $option['updated_at'] = $timestamp;
            }
        }


        /*return [
            'q' => $quiz, 'qs' => $questions, 'op' => $options
        ];*/

        return [
            'q' => FormQuiz::create($quiz),
            'qs' => FormQuestion::insert($questions),
            'op' => $options_count > 0 ? QuestionOption::insert($options) : []
        ];
    }

    public function getOnReview(AcceptQuizTakeForReviewRequest $request)
    {
        $quiz_take = $request->validated('quiz_take');
        $selected = $request->validated('selected');

        $quiz_take['user_id'] = auth()?->id();
        $quiz_take['client_ip'] = $request->ip();
        $quiz_take['user_agent'] = $request->userAgent();
        $timestamp = Carbon::now();

        foreach ($selected as &$option) {
            $option['data'] = json_encode($option['data']);
            $option['created_at'] = $timestamp;
            $option['updated_at'] = $timestamp;
        }


        QuizTake::create($quiz_take);
        QuizTakeOption::insert($selected);

        return response()->json(['message' => 'accepted'], 201);
    }


    public function getQuizTakes(): LengthAwarePaginator
    {
        $user = $this->authenticationService->getAuthenticatedUserX(auth());

        $query = QuizTake::with(['examinee', 'quiz'])
            ->from('of_quiztake as of_qt');

        if($user->isStudent()) {
            $query->where(['of_qt.user_id' => $user->id]);
        }

        return $query->orderBy('created_at', 'desc')->paginate();
    }


    public function getQuizTake(string $id)
    {
        $relations = [
            'quiz' => function (BelongsTo $query) {
                return $query->with(['questions', 'options']);
            },
            'answers',
            'examinee'
        ];

        return QuizTake::with($relations)->find($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(FormQuiz $quiz)
    {
        return [
            'quiz' => $quiz,
            'questions' => $quiz->questions()->get(),
            'options' => $quiz->options()->get(),
            // 'started_at' => now()
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
