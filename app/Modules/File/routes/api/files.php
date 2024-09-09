<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\File\Http\Controllers\FilesController;


Route::group(['middleware' => [IsUserActivated::class, IsAdmin::class], 'prefix' => 'files'], function () {
    Route::get('/', [FilesController::class, 'index'])->name('files.index');
    Route::post('/search', [FilesController::class, 'search'])->name('files.search');
    Route::post('/upload', [FilesController::class, 'store'])->name('files.store')->withoutMiddleware(IsAdmin::class);
    Route::post('/ckeditor/upload', [FilesController::class, 'ckeditorFileUpload'])->name('files.ckeditor-upload');

    Route::delete('{file}', [FilesController::class, 'destroy'])->name('files.destroy');
});

