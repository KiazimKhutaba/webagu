<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Account\Http\Controllers\UserActivityController;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['api', IsUserActivated::class, IsAdmin::class], 'prefix' => 'logs'], function(Router $router) {
    $router->get('/', [UserActivityController::class, 'index']);
    $router->post('/', [UserActivityController::class, 'index']);
    $router->get('/ipsinfo', [UserActivityController::class, 'getUsersIpInfo']); // Todo: routes with exact names should be first of parametrized routes
    $router->get('/unique-ips', [UserActivityController::class, 'getUniqueIPs']);
    $router->get('/unique-urls', [UserActivityController::class, 'getUniqueUrls']);
    $router->get('/most-active-users', [UserActivityController::class, 'getMostActiveUsers']);
    $router->get('/{user_id}', [UserActivityController::class, 'show']);
});
