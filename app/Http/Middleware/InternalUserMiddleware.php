<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Response;

class InternalUserMiddleware
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

        if ( auth()->user()->type != User::USER_TYPE_INTERNAL ) {
            return response([
                "code" => "HTTP_UNAUTHORIZED"
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);

    }
}
