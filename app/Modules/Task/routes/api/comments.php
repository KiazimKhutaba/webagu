<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Task\Http\Controllers\CommentController;
use Illuminate\Routing\Router;

Route::group(['middleware' => [IsUserActivated::class, IsAdmin::class], 'prefix' => 'comments'], function (Router $router) {
    $router->post('', [CommentController::class, 'store'])->name('comments.create')->withoutMiddleware(IsAdmin::class);
    $router->delete('{taskHistoryItem}', [CommentController::class, 'destroy'])->name('comment.destroy');
});
