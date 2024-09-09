<?php

namespace App\Modules\OpenForms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OpenFormsServiceProvider extends ServiceProvider
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
            ->namespace('App\\Modules\\OpenForms\\Controllers\\')
            ->group(__DIR__ . '/../OpenForms/routes/api/openforms.php');
    }
}
