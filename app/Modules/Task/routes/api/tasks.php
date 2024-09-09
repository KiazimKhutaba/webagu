<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserActivated;
use App\Modules\Task\Http\Controllers\StudentTaskController;
use App\Modules\Task\Http\Controllers\TaskController;
use App\Modules\Task\Http\Controllers\TaskReplyController;
use App\Modules\Task\Http\Controllers\UserTasksController;


// Role - Student
Route::group(['middleware' => [IsUserActivated::class], 'prefix' => 'tasks'], function () {
    Route::post('send-for-review', [StudentTaskController::class, 'sendForReview'])->name('tasks.student.send-for-review');
    Route::get('get-student-tasks', [StudentTaskController::class, 'getStudentTasksWithStatuses'])->name('tasks.get-reviews-by-student-id');
});

// Role - Admin
Route::group(['middleware' => ['api', IsUserActivated::class, IsAdmin::class], 'prefix' => 'tasks'], function () {
    Route::get('/', [TaskController::class, 'getListOfTasks'])->name('task.index')->withoutMiddleware(IsAdmin::class);
    Route::post('/', [TaskController::class, 'createTask'])->name('tasks.create-task');
    Route::post('teacher-review', [TaskController::class, 'addTeacherReview'])->name('tasks.history');
    //Route::post('/', [UserTasksController::class, 'store'])->name('task.store');
    Route::get('progress/{id}', [UserTasksController::class, 'getProgressByTasksForUser'])->name('tasks.get_progress_for_user')->withoutMiddleware(IsAdmin::class);
    Route::put('{task}', [UserTasksController::class, 'update'])->name('tasks.update');
    Route::post('{task}/is-visible', [UserTasksController::class, 'toggleVisibility'])->name('tasks.visibility');
    Route::get('{task_id}', [TaskController::class, 'getStudentTask'])->name('task.show')->withoutMiddleware(IsAdmin::class);
    Route::post('{task}/comments', [UserTasksController::class, 'comments'])->name('task.comments'); // Todo: for admin
    Route::delete('{userTask}', [UserTasksController::class, 'destroy'])->name('task.destroy');
});


Route::group([], function () {
    Route::resource('task-reply', TaskReplyController::class)->only(['index', 'store', 'update', 'show', 'delete']);
    Route::get('task-reply/lecture/{lecture_id}/task/{task_id}', [TaskReplyController::class, 'get'])->name('task_reply.from_user');
});


