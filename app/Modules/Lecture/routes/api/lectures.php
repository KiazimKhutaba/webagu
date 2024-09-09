<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Lecture\Http\Controllers\LecturesController;

Route::group(['middleware' => ['api', IsUserActivated::class, IsAdmin::class], 'prefix' => 'lectures'], function () {
    Route::get('', [LecturesController::class, 'index'])->name('lectures.index')->withoutMiddleware(IsAdmin::class);
    Route::get('{lecture}', [LecturesController::class, 'show'])->name('lectures.show')->withoutMiddleware(IsAdmin::class);;
    Route::post('', [LecturesController::class, 'store'])->name('lectures.store');
    Route::put('', [LecturesController::class, 'update'])->name('lectures.update');
    Route::delete('{lecture}', [LecturesController::class, 'destroy'])->name('lectures.destroy');
    Route::get('{lecture}/tasks', [LecturesController::class, 'getTasks'])->name('lectures.tasks');
});

