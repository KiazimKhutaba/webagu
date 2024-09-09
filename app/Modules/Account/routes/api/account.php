<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Account\Http\Controllers\AccountController;

Route::group(['middleware' => [IsUserActivated::class, IsAdmin::class], 'prefix' => 'account'], function () {
    Route::get('{user}', [AccountController::class, 'show'])->name('account.show');
    Route::post('avatar', [AccountController::class, 'saveAvatar'])->name('account.upload_avatar')->withoutMiddleware(IsAdmin::class);
    Route::post('profile-avatar', [AccountController::class, 'saveProfileAvatar'])->name('account.profile-avatar')->withoutMiddleware(IsAdmin::class);
});
