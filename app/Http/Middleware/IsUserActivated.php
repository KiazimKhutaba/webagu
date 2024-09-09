<?php

namespace App\Http\Middleware;

use App\Common\AuthStatusCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserActivated
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if(!$user->isActivated()) {
            // abort(403, 'User is not activated');
            return response()->json(['message' => 'Пользователь не активирован!', 'code' => AuthStatusCode::UserNotActivated], 401);
        }

        return $next($request);
    }
}
