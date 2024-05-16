<?php

namespace App\Providers;

use App\Extensions\MultiApiTokenGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\IllustratorFile' => 'App\Policies\FilePolicy',
        'App\PhotoshopFile' => 'App\Policies\FilePolicy',
        'App\FigmaFile' => 'App\Policies\FilePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerGuards();
        $this->registerGates();

    }

    /**
     * Register guards
     */
    public function registerGuards(){

        Auth::extend('multi-token', function ($app, $name, array $config) {

            $userProvider = app(MultiApiTokenUserProvider::class);
            $request = app('request');
            return new MultiApiTokenGuard($userProvider, $request, $config);

        });

    }

    /**
     * Register guards
     */
    public function registerGates(){

        Gate::define('permission', function ($user, $permission) {
            return $user->hasPermission($permission);
        });

    }
}
