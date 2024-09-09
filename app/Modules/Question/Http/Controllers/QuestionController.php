<?php

namespace App\Modules\Question\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Question\Models\Question;
use App\Modules\Question\Repositories\QuestionsRepository;
use App\Modules\Quiz\Http\Requests\Quiz\QuestionCreateRequest;
use App\Modules\Quiz\Http\Requests\Quiz\QuestionsImportRequest;
use App\Modules\Quiz\Http\Requests\Quiz\QuestionUpdateRequest;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->input('type', '0');
        $theme = $request->input('theme', '0');

        if('0' !== $type) {
            return Question::with('_theme')->where('type', $type)->orderBy('theme')->paginate();
        }

        if('0' !== $theme) {
            return Question::with('_theme')->where('theme', $theme)->orderBy('theme')->paginate();
        }

        return Question::with('_theme')->orderBy('theme')->paginate();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionCreateRequest $request)
    {
        $params = $request->validated();
        $created = Question::create($params);

        return [...$created->toArray(), '_theme' => $created->_theme];
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return $question;
    }


    public function export(QuestionsRepository $questionsRepository)
    {
        $jsonData = json_encode($questionsRepository->export(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="questions.json"',
        ];

        return response($jsonData, 200, $headers);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QuestionUpdateRequest $request, Question $question): array
    {
        $attributes = $request->validated();
        $question->update($attributes);

        return [...$question->toArray(), '_theme' => $question->_theme];
    }



    public function updateTheme(Request $request, Question $question)
    {
        $theme_id = intval($request->get('theme_id'));

        return $question->update(['theme' => $theme_id]);
    }


    public function importQuestions(QuestionsImportRequest $request)
    {
        $list = $request->validated('list');

        $questions = collect($list)->map(function ($item) {
           $item['created_at'] = now();
           $item['updated_at'] = now();
           return $item;
        });

        return ['res' => Question::insert($questions->toArray()) ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        return $question->delete();
    }
}
