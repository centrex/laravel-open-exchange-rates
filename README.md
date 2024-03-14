# This is my package laravel-open-exchange-rates

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/laravel-open-exchange-rates.svg?style=flat-square)](https://packagist.org/packages/centrex/laravel-open-exchange-rates)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-open-exchange-rates/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/laravel-open-exchange-rates/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-open-exchange-rates/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/laravel-open-exchange-rates/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/laravel-open-exchange-rates?style=flat-square)](https://packagist.org/packages/centrex/laravel-open-exchange-rates)

This package provides a simple and convenient interface for working with the Open Exchange Rates service. Currently supports free endpoints.

## Contents

- [This is my package laravel-open-exchange-rates](#this-is-my-package-laravel-open-exchange-rates)
  - [Contents](#contents)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Testing](#testing)
  - [Changelog](#changelog)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)

## Installation

You can install the package via composer:

```bash
composer require centrex/laravel-open-exchange-rates
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-open-exchange-rates-config"
```

This is the contents of the published config file:

```php
return [
    'app_id' => 'your_own_appid',
    'default_base_currency' => 'USD'
];
```

## Usage

In `config/loer.php`
```php
return [
    'app_id' => '*****************************', // your own api key
    'default_base_currency' => 'USD'
];
```
In controller (or service) method:
```php
use Centrex\LaravelOpenExchangeRates\Client;

class SomeController extends Controller
{
    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public function someAction()
    {
        $coefficient = $this->client->latest('USD,RUB,AWG');
        
        $historical_coefficent $this->client->historical('2011-03-05', 'USD,RUB,AWG');
        
        // e.t.c...
        
        // Change in base currencies (not allowed for free account) and requests for the coefficients of all currencies relative to.
        $coefficient = $this->client->currency('RUB')->latest();
    }
}
```

## Testing

🧹 Keep a modern codebase with **Pint**:
```bash
composer lint
```

✅ Run refactors using **Rector**
```bash
composer refacto
```

⚗️ Run static analysis using **PHPStan**:
```bash
composer test:types
```

✅ Run unit tests using **PEST**
```bash
composer test:unit
```

🚀 Run the entire test suite:
```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [rochi88](https://github.com/centrex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
