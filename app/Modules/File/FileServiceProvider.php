<?php

namespace App\Modules\File;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
       $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Modules\\File\\Controllers\\')
            ->group(__DIR__ . '/../File/routes/api/files.php');
    }
}
