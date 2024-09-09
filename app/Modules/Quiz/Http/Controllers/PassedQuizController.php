<?php

namespace App\Modules\Quiz\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Quiz\Http\Requests\Quiz\PassedQuizStoreRequest;
use App\Modules\Quiz\Http\Requests\Quiz\UpdatePassedQuizRequest;
use App\Modules\Quiz\Models\PassedQuiz;

class PassedQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PassedQuiz::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PassedQuizStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['student_id'] = auth()->id();

        return $validated;
    }

    /**
     * Display the specified resource.
     */
    public function show(PassedQuiz $quiz)
    {
        return ['quiz' => $quiz->quiz, 'items' => $quiz->getPassedQuiz()];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePassedQuizRequest $request, PassedQuiz $passedQuiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PassedQuiz $quiz)
    {
        return $quiz->delete();
    }
}
