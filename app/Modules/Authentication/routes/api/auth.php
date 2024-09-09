<?php

use App\Http\Middleware\ApiAuthMiddleware;
use App\Modules\Authentication\Http\Controllers\AuthenticationController;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['api.auth']], function () {

    Route::post('register', [AuthenticationController::class, 'register'])->name('auth.register')->withoutMiddleware('api.auth');
    Route::post('login', [AuthenticationController::class, 'login'])->name('auth.login')->withoutMiddleware('api.auth');
    Route::get('refresh', [AuthenticationController::class, 'refresh'])->name('auth.refresh')->withoutMiddleware('api.auth');
    Route::get('logout', [AuthenticationController::class, 'logout'])->name('auth.logout');

    /**
     * Route::post('send-code', [AuthenticationController::class, 'sendCode'])->name('auth.sendCode')->withoutMiddleware('api.auth');
     * Route::post('check-code', [AuthenticationController::class, 'checkCode'])->name('auth.checkCode')->withoutMiddleware('api.auth');
     * Route::post('send-recovery-code', [AuthenticationController::class, 'sendRecoveryCode'])->name('auth.send_recovery_code')->withoutMiddleware('api.auth');
     * Route::post('change-password', [AuthenticationController::class, 'changePassword'])->name('auth.change_password')->withoutMiddleware('api.auth');
     */

    Route::get('get-authenticated', [AuthenticationController::class, 'getAuthenticated'])->name('customers.get-authenticated');
});
