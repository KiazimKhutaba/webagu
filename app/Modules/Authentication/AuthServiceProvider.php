<?php

namespace App\Modules\Authentication;

use App\Modules\Authentication\Repositories\Token\TokenRepository;
use App\Modules\Authentication\Repositories\Token\TokenRepositoryInterface;
use App\Modules\Authentication\Services\AuthenticationService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
       $this->registerRoutes();
    }

    public function register(): void
    {
        $this->app->when(AuthenticationService::class)
            ->needs('$jwtSecret')
            ->give(env('JWT_SECRET'));

        $this->app->when(AuthenticationService::class)
            ->needs('$refreshTTL')
            ->give(env('JWT_REFRESH_TOKEN_TTL'));

        $this->app->singleton(TokenRepositoryInterface::class, TokenRepository::class);
    }

    protected function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\\Modules\\Authentication\\Controllers\\')
            ->group(__DIR__ . '/routes/auth.php');
    }
}
