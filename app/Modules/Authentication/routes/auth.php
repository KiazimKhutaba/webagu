<?php

use App\Modules\Authentication\Http\Controllers\AuthController;

Route::group(['middleware' => [], 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
    Route::post('register', [AuthController::class, 'register'])->name('auth.create');
    Route::get('me', [AuthController::class, 'me'])->name('auth.me');
});
