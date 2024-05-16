<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        //
        $this->configureRateLimiting();
        parent::boot();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(300);
        });

        RateLimiter::for('asset-download', function (Request $request) {
            return Limit::perMinute(2000);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapApiSystemRoutes();
        $this->mapApiAdminRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapApiSystemRoutes()
    {
        Route::prefix('api/v1/')
            ->middleware('api-system')
            ->namespace($this->namespace)
            ->group(base_path('routes/api-system.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapApiAdminRoutes()
    {
        Route::prefix('api/v1/admin/')
            ->middleware('api-admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/api-admin.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api/v1/')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
