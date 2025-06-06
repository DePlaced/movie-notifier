<?php

namespace App\Providers;

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
        $this->app->scoped(\App\Client\IMovieClient::class,\App\Client\MovieClient::class);
        $this->app->scoped(\App\Client\ITmdbClient::class,\App\Client\TmdbClient::class);
    }
}
