<?php

namespace App\Modules\Lecture;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LectureServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
       $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Modules\\Lecture\\Controllers\\')
            ->group(__DIR__ . '/../Lecture/routes/api/lectures.php');
    }
}
