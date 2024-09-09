<?php

namespace App\Modules\Quiz\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Quiz\Http\Requests\Quiz\StoreUserQuizRequest;
use App\Modules\Quiz\Http\Requests\Quiz\UpdateUserQuizRequest;
use App\Modules\Quiz\Models\UserQuiz;

class UserQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserQuizRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserQuiz $userQuiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserQuizRequest $request, UserQuiz $userQuiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserQuiz $userQuiz)
    {
        //
    }
}
