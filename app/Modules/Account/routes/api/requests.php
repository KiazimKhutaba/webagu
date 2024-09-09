<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Account\Http\Controllers\RequestsController;

Route::group(['middleware' => ['api', IsUserActivated::class, IsAdmin::class], 'prefix' => 'requests'], function () {
    Route::put('{model}', [RequestsController::class, 'updateApproveStatus']);
    Route::get('/', [RequestsController::class, 'index'])->name('requests.all');
    Route::post('password-change-request', [RequestsController::class, 'passwordChangeRequest'])->withoutMiddleware([IsUserActivated::class, IsAdmin::class]);
    Route::get('password-changes-requests', [RequestsController::class, 'getAllPasswordChangeRequests'])->name('requests.password-changes-requests');
    Route::delete('{id}', [RequestsController::class, 'destroy'])->name('requests.remove');
});
