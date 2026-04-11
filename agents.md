# agents.md

## Agent Guidance — laravel-open-exchange-rates

### Package Purpose
HTTP client for the Open Exchange Rates API. Fetches live and historical currency exchange rates and makes them available in Laravel apps.

### Before Making Changes
- Read `src/Client.php` — all API calls go through here
- Read `config/config.php` — understand configurable options (app_id, base currency, cache TTL)
- Check `src/Exceptions/` — API errors should throw typed exceptions, not return nulls

### Common Tasks

**Adding a new API endpoint**
1. Add a method to `src/Client.php`
2. Use Laravel's `Http` facade — not raw Guzzle
3. Throw a typed exception from `src/Exceptions/` on non-2xx responses
4. Cache responses where appropriate using `config('open-exchange-rates.cache_ttl')`
5. Add a test using `Http::fake()` with a fixture response

**Adding response caching**
- Use Laravel's Cache facade with a TTL from config
- Cache key should include the endpoint + parameters (e.g., `oer.latest.USD`)
- Never cache error responses

**Updating for API changes**
- Open Exchange Rates API versioning is in the base URL — check `config/config.php`
- If the response schema changes, update the return type/shape documentation and tests

### Testing
```sh
composer test:unit        # pest — uses Http::fake() for all API calls
composer test:types       # phpstan
composer test:lint        # pint
```

Always mock HTTP in tests — never make real API calls:
```php
Http::fake(['openexchangerates.org/*' => Http::response(['rates' => ['EUR' => 0.92]])]);
```

### Environment Variables
```env
OPEN_EXCHANGE_RATES_APP_ID=your_app_id_here
```

### Safe Operations
- Adding new API endpoint wrappers
- Improving caching strategy
- Adding typed exceptions
- Adding tests with Http::fake()

### Risky Operations — Confirm Before Doing
- Changing the base URL or API version in config (existing installs may have this cached)
- Removing caching (could cause rate limit issues in host apps)
- Changing exception class names (breaks host app catch blocks)

### Do Not
- Make real HTTP calls in tests
- Hardcode the `app_id` anywhere in source
- Return `null` on API errors — throw a typed exception instead
- Skip `declare(strict_types=1)` in any new file
