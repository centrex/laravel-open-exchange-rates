<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates;

use Illuminate\Support\ServiceProvider;

class LaravelOpenExchangeRatesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-open-exchange-rates');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-open-exchange-rates');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-open-exchange-rates.php'),
            ], 'laravel-open-exchange-rates-config');

            // Publishing the migrations.
            /*$this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'laravel-open-exchange-rates-migrations');*/

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-open-exchange-rates'),
            ], 'laravel-open-exchange-rates-views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-open-exchange-rates'),
            ], 'laravel-open-exchange-rates-assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-open-exchange-rates'),
            ], 'laravel-open-exchange-rates-lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-open-exchange-rates');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-open-exchange-rates', function () {
            return new Client();
        });
    }
}
