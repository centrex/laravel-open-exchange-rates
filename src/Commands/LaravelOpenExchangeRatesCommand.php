<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates\Commands;

use Illuminate\Console\Command;

class LaravelOpenExchangeRatesCommand extends Command
{
    public $signature = 'laravel-open-exchange-rates';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
