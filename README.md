# Laravel Open Exchange Rates

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/laravel-open-exchange-rates.svg?style=flat-square)](https://packagist.org/packages/centrex/laravel-open-exchange-rates)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-open-exchange-rates/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/laravel-open-exchange-rates/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-open-exchange-rates/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/laravel-open-exchange-rates/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/laravel-open-exchange-rates?style=flat-square)](https://packagist.org/packages/centrex/laravel-open-exchange-rates)

Laravel client for the [Open Exchange Rates API](https://openexchangerates.org). Provides access to latest rates, historical rates, currency conversion, OHLC data, time-series, and account usage.

## Installation

```bash
composer require centrex/laravel-open-exchange-rates
php artisan vendor:publish --tag="laravel-open-exchange-rates-config"
```

Add your App ID to `.env`:

```env
OPEN_EXCHANGE_RATES_APP_ID=your_app_id_here
```

## Usage

```php
use Centrex\LaravelOpenExchangeRates\Facades\OpenExchangeRates;

// Latest rates (all currencies)
$rates = OpenExchangeRates::latest();

// Latest rates for specific currencies
$rates = OpenExchangeRates::latest('USD,EUR,BDT,GBP');

// Historical rates for a date
$rates = OpenExchangeRates::historical('2024-01-01');
$rates = OpenExchangeRates::historical('2024-01-01', 'USD,EUR');

// Convert a value between currencies
$result = OpenExchangeRates::convert(100, 'USD', 'BDT');

// Full list of available currencies
$currencies = OpenExchangeRates::currencies();
$currencies = OpenExchangeRates::currencies(showAlternative: '1');

// Time-series rates between two dates
$series = OpenExchangeRates::timeSeries('2024-01-01', '2024-01-31', 'USD,EUR');

// OHLC (Open, High, Low, Close) for a period
// Allowed periods: 1m, 5m, 15m, 30m, 1h, 12h, 1d, 1w, 1mo
$ohlc = OpenExchangeRates::ohlc('2024-01-01T00:00:00Z', '1d', 'USD,EUR');

// Account usage stats
$usage = OpenExchangeRates::usage();
```

All methods return a decoded array. An `OpenExchangeRatesResponseException` is thrown on API errors.

## Testing

```bash
composer test        # full suite
composer test:unit   # pest only
composer test:types  # phpstan
composer lint        # pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [centrex](https://github.com/centrex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
