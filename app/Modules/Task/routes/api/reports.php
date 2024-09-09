<?php


use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Account\Http\Controllers\UsersController;
use App\Modules\Task\Http\Controllers\TaskReportsController;

Route::group(['middleware' => [IsUserActivated::class, IsAdmin::class], 'prefix' => 'reports'], function () {
    Route::get('/get-task-statuses', [UsersController::class, 'getTaskStatusesForAllStudents'])->name('reports.get_task_statuses');
    Route::get('/tasks-progress', [TaskReportsController::class, 'getTasksReportsProgress'])->name('reports.tasks_progress');
});
