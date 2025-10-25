<?php

namespace OnMagik\LaravelClient;

use Illuminate\Support\ServiceProvider;
use OnMagik\LaravelClient\Services\ONClient;

class ONServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/Config/on.php', 'on'
        );

        // Register ONClient as singleton
        $this->app->singleton(ONClient::class, function ($app) {
            return new ONClient(
                config('on.api_url'),
                config('on.api_key'),
                config('on.bearer_token')
            );
        });

        // Register facade alias
        $this->app->alias(ONClient::class, 'on');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/Config/on.php' => config_path('on.php'),
        ], 'on-config');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}