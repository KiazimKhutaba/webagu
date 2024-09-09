<?php

namespace App\Modules\Quiz;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class QuizServiceProvider extends ServiceProvider
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
            ->namespace('App\\Modules\\Quiz\\Http\\Controllers\\')
            ->group(__DIR__ . '/../Quiz/routes/api/quizzes.php');
    }
}
