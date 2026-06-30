<?php

declare(strict_types = 1);

use Centrex\LaravelOpenExchangeRates\Models\ExchangeRate;

it('stores a daily snapshot of rates per base currency', function (): void {
    ExchangeRate::upsertRates(['GBP' => 0.8, 'BDT' => 120], 'USD', new DateTime('2026-04-10 10:00:00'));

    $row = ExchangeRate::query()->forBase('USD')->onDate('2026-04-10')->first();

    expect($row)->not->toBeNull()
        ->and($row->rateFor('GBP'))->toBe(0.8)
        ->and($row->rateFor('BDT'))->toBe(120.0)
        ->and($row->rateFor('EUR'))->toBeNull();
});

it('merges new currencies into an existing daily snapshot instead of overwriting it', function (): void {
    ExchangeRate::upsertRates(['GBP' => 0.8], 'USD', new DateTime('2026-04-10 10:00:00'), '2026-04-10');
    ExchangeRate::upsertRates(['BDT' => 120], 'USD', new DateTime('2026-04-10 11:00:00'), '2026-04-10');

    expect(ExchangeRate::query()->forBase('USD')->onDate('2026-04-10')->count())->toBe(1);

    $row = ExchangeRate::query()->forBase('USD')->onDate('2026-04-10')->first();

    expect($row->rateFor('GBP'))->toBe(0.8)
        ->and($row->rateFor('BDT'))->toBe(120.0);
});

it('finds the latest snapshot containing a currency', function (): void {
    ExchangeRate::upsertRates(['BDT' => 118], 'USD', new DateTime('2026-04-09 10:00:00'), '2026-04-09');
    ExchangeRate::upsertRates(['BDT' => 120], 'USD', new DateTime('2026-04-10 10:00:00'), '2026-04-10');

    $latest = ExchangeRate::latestFor('BDT', 'USD');

    expect($latest->date->toDateString())->toBe('2026-04-10')
        ->and($latest->rateFor('BDT'))->toBe(120.0);
});

it('finds the snapshot as of a given date, falling back to the most recent prior date', function (): void {
    ExchangeRate::upsertRates(['BDT' => 118], 'USD', new DateTime('2026-04-09 10:00:00'), '2026-04-09');
    ExchangeRate::upsertRates(['BDT' => 120], 'USD', new DateTime('2026-04-11 10:00:00'), '2026-04-11');

    $asOf = ExchangeRate::asOf('BDT', 'USD', '2026-04-10');

    expect($asOf->date->toDateString())->toBe('2026-04-09')
        ->and($asOf->rateFor('BDT'))->toBe(118.0);
});

it('converts amounts using the rate for a currency', function (): void {
    ExchangeRate::upsertRates(['BDT' => 120], 'USD', new DateTime('2026-04-10 10:00:00'), '2026-04-10');

    $row = ExchangeRate::query()->forBase('USD')->onDate('2026-04-10')->first();

    expect($row->convertTo(10, 'BDT'))->toBe(1200.0)
        ->and($row->convertFrom(1200, 'BDT'))->toBe(10.0)
        ->and($row->convertTo(10, 'EUR'))->toBe(0.0);
});
