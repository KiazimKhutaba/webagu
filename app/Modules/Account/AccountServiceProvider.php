<?php

namespace App\Modules\Account;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
       $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Modules\\Account\\Http\\Controllers\\')
            ->group([
                __DIR__ . '/routes/api/account.php',
                __DIR__ . '/routes/api/users.php',
                __DIR__ . '/routes/api/requests.php',
                __DIR__ . '/routes/api/logs.php',
            ]);
    }
}
