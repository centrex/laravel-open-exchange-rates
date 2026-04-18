<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates\Commands;

use Centrex\LaravelOpenExchangeRates\Client;
use Centrex\LaravelOpenExchangeRates\Models\ExchangeRate;
use Illuminate\Console\Command;

class SyncExchangeRatesCommand extends Command
{
    public $signature = 'open-exchange-rates:sync
                        {--symbols= : Comma-separated list of currency codes to fetch (default: all)}
                        {--date=    : Fetch historical rates for this date (YYYY-MM-DD); omit for latest}';

    public $description = 'Fetch exchange rates from Open Exchange Rates and persist them locally';

    public function handle(Client $client): int
    {
        $symbols = (string) $this->option('symbols');
        $date    = (string) $this->option('date');

        try {
            $response = $date !== ''
                ? $client->historical($date, $symbols)
                : $client->latest($symbols);
        } catch (\Throwable $e) {
            $this->error('Failed to fetch rates: ' . $e->getMessage());

            return self::FAILURE;
        }

        $base      = strtoupper($response['base'] ?? config('laravel-open-exchange-rates.default_base_currency', 'USD'));
        $rates     = $response['rates'] ?? [];
        $fetchedAt = now();

        if (empty($rates)) {
            $this->warn('No rates returned.');

            return self::SUCCESS;
        }

        ExchangeRate::upsertRates($rates, $base, $fetchedAt);

        $this->info(sprintf('Synced %d exchange rates (base: %s).', count($rates), $base));

        return self::SUCCESS;
    }
}
