<?php

namespace App\Http;

use App\Http\Middleware\CheckIntegrationHeader;
use App\Http\Middleware\FrameHeadersMiddleware;
use App\Http\Middleware\InternalUserMiddleware;
use App\Http\Middleware\SystemUserMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\AppTokenMiddleware;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api-system' => [
            'bindings',
        ],
        'api-admin' => [ 
            'bindings',
        ],
        'api' => [
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.system' => SystemUserMiddleware::class,
        'auth.internal' => InternalUserMiddleware::class,
        'auth.app-token' => AppTokenMiddleware::class,
        'allow.frame' => FrameHeadersMiddleware::class,
        'auth.integrations-check' => CheckIntegrationHeader::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    // /**
    //  * The priority-sorted list of middleware.
    //  *
    //  * This forces non-global middleware to always be in the given order.
    //  *
    //  * @var array
    //  */
    // protected $middlewarePriority = [
    //     \Illuminate\Session\Middleware\StartSession::class,
    //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    //     \App\Http\Middleware\Authenticate::class,
    //     \Illuminate\Session\Middleware\AuthenticateSession::class,
    //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
    //     \Illuminate\Auth\Middleware\Authorize::class,
    // ];
}
