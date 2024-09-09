<?php

namespace App\Modules\Task;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRoutes();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Modules\\Task\\Http\\Controllers\\')
            ->group([
                __DIR__ . '/routes/api/tasks.php',
                __DIR__ . '/routes/api/comments.php',
                __DIR__ . '/routes/api/reports.php',
                __DIR__ . '/routes/api/reviews.php',
            ]);
    }
}
