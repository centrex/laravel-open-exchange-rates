<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates;

use Centrex\LaravelOpenExchangeRates\Commands\SyncExchangeRatesCommand;
use Illuminate\Support\ServiceProvider;

class LaravelOpenExchangeRatesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-open-exchange-rates.php'),
            ], 'laravel-open-exchange-rates-config');


            $this->publishes([
                    __DIR__ . '/../database/migrations/' => database_path('migrations'),
                ], 'laravel-open-exchange-rates-migrations');
            

            $this->commands([SyncExchangeRatesCommand::class]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-open-exchange-rates');

        $this->app->singleton('laravel-open-exchange-rates', fn (): Client => new Client());
        $this->app->alias('laravel-open-exchange-rates', Client::class);
    }
}
