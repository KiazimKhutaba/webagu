<?php


use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Post\Http\Controllers\PostController;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['api', IsUserActivated::class, IsAdmin::class], 'prefix' => 'posts'], function(Router $router) {
    $router->post('', [PostController::class, 'store'])->name('posts.store');
    $router->delete('{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    $router->get('/', [PostController::class, 'index'])->withoutMiddleware(IsAdmin::class)->name('posts.index');
    $router->get('/unread-posts-count', [PostController::class, 'unreadPostsCount'])->withoutMiddleware(IsAdmin::class); // Todo: When user is not activated this endpoint causes infinite reload
    $router->get('{post}', [PostController::class, 'show'])->withoutMiddleware(IsAdmin::class)->name('posts.show');
});

