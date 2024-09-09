<?php

namespace App\Http\Middleware;

use App\Modules\Account\Services\UserActionsLoggingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function in_array;

class LogUserAction
{
    public function __construct(
        private readonly UserActionsLoggingService $loggingService
    )
    {}


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->user()?->isAdmin())
        {
            if(!in_array($request->route()->getName(), ['auth.login', 'account.create', 'account.password_change_request']))
            {
                $user_id = auth()?->id();

                if($user_id) {
                    $this->loggingService->log($request, $user_id);
                }

                //Log::info(json_encode($resp));
            }
        }

        return $next($request);
    }
}
