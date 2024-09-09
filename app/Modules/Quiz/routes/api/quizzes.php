<?php


// Todo: add IsAdmin middleware?
use App\Http\Middleware\IsUserActivated;
use App\Modules\Quiz\Http\Controllers\QuizController;

Route::group(['middleware' => ['api', IsUserActivated::class], 'prefix' => 'quizzes'], function () {
    Route::get('', [QuizController::class, 'index'])->name('quizzes.list');
    Route::post('', [QuizController::class, 'store'])->name('quizzes.create');
    Route::post('grant-access', [QuizController::class, 'grantUserAccessToQuiz'])->name('quizzes.grant-access');
    Route::delete('remove-reply-file/{id}', [QuizController::class, 'removeReplyFile'])->name('quizzes.remove-reply-file');
    Route::post('add-reply-file', [QuizController::class, 'addReplyFile'])->name('quizzes.add-reply-file');
    Route::put('send-on-review', [QuizController::class, 'sendOnReview'])->name('quizzes.send-on-review');
    Route::get('passed', [QuizController::class, 'getPassedQuizzes'])->name('quizzes.all_passed'); // Todo: this should be first, as it conflicts with
    Route::get('passed/{quiz}', [QuizController::class, 'getPassedQuiz'])->name('quizzes.one_passed');
    Route::get('{quiz}', [QuizController::class, 'beginQuiz'])->name('quizzes.begin');
    Route::post('{quiz}/status', [QuizController::class, 'toggleStatus'])->name('quizzes.toggle_status');
    Route::get('{uuid}/access-requests', [QuizController::class, 'getQuizAccessRequests'])->name('quizzes.access-requests');
});
