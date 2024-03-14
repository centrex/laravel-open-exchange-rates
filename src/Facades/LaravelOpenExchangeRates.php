<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Centrex\LaravelOpenExchangeRates\LaravelOpenExchangeRates
 */
class LaravelOpenExchangeRates extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Centrex\LaravelOpenExchangeRates\Client::class;
    }
}
