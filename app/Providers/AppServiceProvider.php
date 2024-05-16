<?php

namespace App\Providers;

use App\AccessCode;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Validator::extend('access_code', function($attribute, $value, $parameters) {
            return !!AccessCode::unused()->where('code', $value)->first();
        });

    }
}
