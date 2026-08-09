# Known Issues — laravel-open-exchange-rates

_Last checked: 2026-08-02_

## Failing tests

No failing tests. `vendor/bin/pest -p` — 7 passed (18 assertions).

## Style / static-analysis debt

- `composer test` stops early: `rector --dry-run` (test:refacto) fails — 4 files would change, all the same rule (`AddOverrideAttributeToOverriddenMethodsRector` — missing `#[\Override]` on overridden methods): `src/Facades/LaravelOpenExchangeRates.php:10`, `src/LaravelOpenExchangeRatesServiceProvider.php:25`, `src/Models/ExchangeRate.php:11`, `tests/TestCase.php:9`. Run `composer refacto` to apply.
- `vendor/bin/pint --test` — passes, no style issues.
- `vendor/bin/phpstan analyse` (level max) — **33 errors**. `phpstan-baseline.neon` exists but is empty (0 lines), so all 33 errors are unbaselined/live. Most are in `src/Models/ExchangeRate.php`: string literals passed to `where()`/`updateOrCreate()` where Larastan wants `model property of ExchangeRate` (e.g. lines 42, 57, 83, 87, 88 — `'base'`, `'date'`, `'fetched_at'`, `'rates'`), missing iterable value types on `upsertRates()` (line 68) and other array params, missing generic `TModel` type on `scopeForBase()`/`scopeOnDate()` (lines 121, 123, 126), and a mixed-typed argument to `setConnection()` (line 31).

## TODO / FIXME markers

None found.

## Open GitHub issues

Not checked — the `gh` CLI is not installed in this environment.
