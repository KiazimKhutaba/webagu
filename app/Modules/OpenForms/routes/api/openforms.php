<?php


// OpenForms
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\OpenForms\Http\Controllers\FormQuizController;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['api', IsUserActivated::class], 'prefix' => 'openforms'], function(Router $router) {
    $router->post('', [FormQuizController::class, 'createFormQuiz'])->middleware(IsAdmin::class);

    $router->get('', [FormQuizController::class, 'getListOfFormQuizzes']);
    $router->post('send-for-review', [FormQuizController::class, 'getOnReview'])->name('openfroms.quiz_on_review');
    $router->get('get-passed-quizzes', [FormQuizController::class, 'getQuizTakes'])->name('openforms.get-passed-quizzes');
    $router->get('quiztake/{id}', [FormQuizController::class, 'getQuizTake'])->name('openforms.quiztake');

    $router->get('{quiz}', [FormQuizController::class, 'show']);

});
