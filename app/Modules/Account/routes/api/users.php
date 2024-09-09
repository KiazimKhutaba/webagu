<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Account\Http\Controllers\UsersController;

Route::group(['middleware' => [IsUserActivated::class, IsAdmin::class], 'prefix' => 'users'], function () {
    Route::get('/', [UsersController::class, 'index'])->name('users.index');
    Route::get('students', [UsersController::class, 'getStudents'])->name('users.get_students');
    Route::post('search', [UsersController::class, 'search'])->name('users.search');
    Route::post('change-activation-status', [UsersController::class, 'changeActivationStatus'])->name('users.change_activation_status');
    Route::get('{user}', [UsersController::class, 'show'])->name('users.show');
    /*Route::post('', [LecturesController::class, 'store'])->name('lectures.store');
    Route::delete('{user}', [LecturesController::class, 'destroy'])->name('lectures.destroy');*/
});
