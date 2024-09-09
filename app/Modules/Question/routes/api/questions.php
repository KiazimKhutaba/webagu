<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Question\Http\Controllers\QuestionController;
use Illuminate\Routing\Router;

Route::group(['middleware' => [IsUserActivated::class, IsAdmin::class]], function (Router $router) {
    $router->get('/questions/export', [QuestionController::class, 'export'])->name('questions.export');
    $router->apiResource('questions', QuestionController::class);
    $router->post('/questions/import', [QuestionController::class, 'importQuestions'])->name('questions.import');
    $router->patch('/questions/{question}/theme', [QuestionController::class, 'updateTheme'])->name('questions.update_theme');
});

