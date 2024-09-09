<?php

namespace App\Modules\Post;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
       $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Modules\\Post\\Controllers\\')
            ->group(__DIR__ . '/../Post/routes/api/posts.php');
    }
}
