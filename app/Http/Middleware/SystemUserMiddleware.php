<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Response;

class SystemUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ( !auth()->user()->hasPermissionTo(User::PERMISSION_SYSTEM_ACCESS) ) {
            return response([
                "code" => "HTTP_UNAUTHORIZED"
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);

    }
}
