<?php


use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Task\Http\Controllers\TaskReviewController;
use Illuminate\Routing\Router;

Route::group(['middleware' => [IsUserActivated::class, IsAdmin::class], 'prefix' => 'reviews'], function (Router $router) {
    $router->get('', [TaskReviewController::class, 'getTasksSentByStudents'])->name('reviews.index');
    $router->post('', [TaskReviewController::class, 'store'])->name('reviews.user_sent_for_review')->withoutMiddleware(IsAdmin::class);
    $router->get('/task/{task}/student/{student}', [TaskReviewController::class, 'getReview'])->name('reviews.get_for_student');
});
