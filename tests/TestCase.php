<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates\Tests;

use Centrex\LaravelOpenExchangeRates\LaravelOpenExchangeRatesServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'Centrex\\LaravelOpenExchangeRates\\Database\\Factories\\' . class_basename($modelName) . 'Factory',
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelOpenExchangeRatesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-open-exchange-rates_table.php.stub';
        $migration->up();
        */
    }
}
