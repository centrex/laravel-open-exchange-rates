# CLAUDE.md

## Package Overview

`centrex/laravel-open-exchange-rates` — Open Exchange Rates API client for Laravel.

Namespace: `Centrex\LaravelOpenExchangeRates\`  
Service Provider: `LaravelOpenExchangeRatesServiceProvider`  
Main class: `Client`  
Facade: `Facades/`

## Commands

Run from inside this directory (`cd laravel-open-exchange-rates`):

```sh
composer install          # install dependencies
composer test             # full suite: rector dry-run, pint check, phpstan, pest
composer test:unit        # pest tests only
composer test:lint        # pint style check (read-only)
composer test:types       # phpstan static analysis
composer test:refacto     # rector refactor check (read-only)
composer lint             # apply pint formatting
composer refacto          # apply rector refactors
composer analyse          # phpstan (alias)
composer build            # prepare testbench workbench
composer start            # build + serve testbench dev server
```

Run a single test:
```sh
vendor/bin/pest tests/ExampleTest.php
vendor/bin/pest --filter "test name"
```

## Structure

```
src/
  Client.php                            # HTTP client for Open Exchange Rates API
  LaravelOpenExchangeRatesServiceProvider.php
  Facades/
  Commands/
  Exceptions/
config/config.php
tests/
workbench/
```

## Configuration

Set your API key in `.env`:

```env
OPEN_EXCHANGE_RATES_APP_ID=your_app_id_here
```

## Conventions

- PHP 8.2+, `declare(strict_types=1)` in all files
- Pest for tests, snake_case test names
- Pint with `laravel` preset
- Rector targeting PHP 8.3 with `CODE_QUALITY`, `DEAD_CODE`, `EARLY_RETURN`, `TYPE_DECLARATION`, `PRIVATIZATION` sets
- PHPStan at level `max` with Larastan
