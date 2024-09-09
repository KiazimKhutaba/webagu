<?php

namespace App\Modules\Question;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class QuestionServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
       $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Modules\\Question\\Http\\Controllers\\')
            ->group(__DIR__ . '/../Question/routes/api/questions.php');
    }
}
