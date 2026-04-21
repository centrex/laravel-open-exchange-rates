<?php

declare(strict_types = 1);

namespace Centrex\LaravelOpenExchangeRates\Concerns;

trait AddTablePrefix
{
    public function getTable(): string
    {
        $prefix = config('laravel-open-exchange-rates.table_prefix') ?: 'oer_';

        return $prefix . $this->getTableSuffix();
    }

    abstract protected function getTableSuffix(): string;
}
